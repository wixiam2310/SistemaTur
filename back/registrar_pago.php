<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['rol']!='Encargado Zona Turistica')
{
    die("Acceso denegado");
}

function h($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function dinero($valor)
{
    return '$'.number_format((float)$valor, 2).' MXN';
}

function calcular_noches($fecha_check_in, $fecha_check_out)
{
    $inicio = strtotime($fecha_check_in);
    $fin = strtotime($fecha_check_out);

    if($inicio === false || $fin === false || $fin <= $inicio)
    {
        return 0;
    }

    return (int)floor(($fin - $inicio) / 86400);
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $id_reservacion = (int)$_POST['id_reservacion'];
    $monto = (float)$_POST['monto'];
    $tipo = $_POST['tipo'];
    $concepto = pg_escape_string($conn,$_POST['concepto']);

    $reserva = pg_query(
    $conn,
    "
    SELECT
    r.*,
    h.tipo AS tipo_habitacion
    FROM reservacion r
    INNER JOIN habitacion h
    ON r.id_habitacion = h.id_habitacion
    WHERE r.id_reservacion = $id_reservacion
    "
    );

    if(pg_num_rows($reserva)==0)
    {
        die("Reservación no encontrada");
    }

    $r = pg_fetch_assoc($reserva);

    $total_reserva = (float)$r['total'];
    $tipo_habitacion = $r['tipo_habitacion'];

    $pagos_previos = pg_query(
    $conn,
    "
    SELECT COALESCE(SUM(monto),0) AS total_pagado
    FROM pago
    WHERE id_reservacion = $id_reservacion
    AND estado='Valido'
    "
    );

    $p = pg_fetch_assoc($pagos_previos);

    $total_pagado = (float)$p['total_pagado'];

    $saldo_actual = $total_reserva - $total_pagado;

    if($saldo_actual <= 0)
    {
        die("La reservación ya está liquidada.");
    }

    /*
    ==============================
    DEPOSITO MINIMO RN-05
    Solo aplica a habitaciones Presidenciales.
    ==============================
    */

    if($tipo=='Deposito' && $tipo_habitacion=='Presidencial')
    {
        $deposito_minimo = $total_reserva * 0.30;

        if($monto < $deposito_minimo)
        {
            die(
            "El depósito mínimo para una habitación Presidencial es de "
            .dinero($deposito_minimo)
            );
        }
    }

    /*
    ==============================
    NO PERMITIR PAGAR DE MAS
    ==============================
    */

    if($monto > $saldo_actual)
    {
        die(
        "El pago excede el saldo pendiente. "
        ."Saldo actual: "
        .dinero($saldo_actual)
        );
    }

    /*
    ==============================
    TIPO TOTAL
    ==============================
    */

    if($tipo=='Total' && abs($monto - $saldo_actual) > 0.01)
    {
        die(
        "Para liquidar la reservación debes pagar exactamente "
        .dinero($saldo_actual)
        );
    }

    $sql = "
    INSERT INTO pago
    (
    id_reservacion,
    monto,
    tipo,
    estado,
    concepto
    )
    VALUES
    (
    $id_reservacion,
    $monto,
    '$tipo',
    'Valido',
    '$concepto'
    )
    ";

    $resultado = pg_query($conn,$sql);

    if(!$resultado)
    {
        die(pg_last_error($conn));
    }

    $nuevo_total = $total_pagado + $monto;

    if($nuevo_total >= $total_reserva)
    {
        pg_query(
        $conn,
        "
        UPDATE reservacion
        SET estado='Confirmada'
        WHERE id_reservacion = $id_reservacion
        "
        );
    }

    header("Location: pagos.php");
    exit();
}

$reservaciones = pg_query(
$conn,
"
SELECT
r.id_reservacion,
u.nombre,
u.apellido,
h.numero,
h.tipo AS tipo_habitacion,
r.total,
r.estado,
(
SELECT COALESCE(SUM(p.monto),0)
FROM pago p
WHERE p.id_reservacion = r.id_reservacion
AND p.estado='Valido'
) AS total_pagado
FROM reservacion r
INNER JOIN usuario u
ON r.id_usuario = u.id_usuario
INNER JOIN habitacion h
ON r.id_habitacion = h.id_habitacion
WHERE r.estado <> 'Cancelada'
ORDER BY r.id_reservacion DESC
"
);

$lista_reservaciones = array();

while($fila = pg_fetch_assoc($reservaciones))
{
    $lista_reservaciones[] = $fila;
}

$id_reservacion_seleccionada = 0;

if(isset($_GET['id_reservacion']))
{
    $id_reservacion_seleccionada = (int)$_GET['id_reservacion'];
}
elseif(count($lista_reservaciones) > 0)
{
    $id_reservacion_seleccionada = (int)$lista_reservaciones[0]['id_reservacion'];
}

$detalle = null;
$historial_pagos = array();

if($id_reservacion_seleccionada > 0)
{
    $detalle_resultado = pg_query(
    $conn,
    "
    SELECT
    r.id_reservacion,
    r.fecha_check_in,
    r.fecha_check_out,
    r.estado,
    r.total,
    u.nombre,
    u.apellido,
    h.numero,
    h.edificio,
    h.tipo AS tipo_habitacion,
    h.precio_base,
    t.nombre AS temporada,
    t.incremento_porcentaje,
    (
    SELECT COALESCE(SUM(p.monto),0)
    FROM pago p
    WHERE p.id_reservacion = r.id_reservacion
    AND p.estado='Valido'
    ) AS total_pagado
    FROM reservacion r
    INNER JOIN usuario u
    ON r.id_usuario = u.id_usuario
    INNER JOIN habitacion h
    ON r.id_habitacion = h.id_habitacion
    INNER JOIN temporada t
    ON r.id_temporada = t.id_temporada
    WHERE r.id_reservacion = $id_reservacion_seleccionada
    "
    );

    if(pg_num_rows($detalle_resultado) > 0)
    {
        $detalle = pg_fetch_assoc($detalle_resultado);
    }

    $historial_resultado = pg_query(
    $conn,
    "
    SELECT
    fecha_pago,
    tipo,
    concepto,
    monto,
    estado
    FROM pago
    WHERE id_reservacion = $id_reservacion_seleccionada
    ORDER BY fecha_pago DESC, id_pago DESC
    "
    );

    while($pago = pg_fetch_assoc($historial_resultado))
    {
        $historial_pagos[] = $pago;
    }
}

$noches = 0;
$precio_aplicado = 0;
$total_pagado = 0;
$saldo_pendiente = 0;
$deposito_minimo = 0;
$cumple_deposito = false;

if($detalle)
{
    $noches = calcular_noches($detalle['fecha_check_in'], $detalle['fecha_check_out']);
    $precio_base = (float)$detalle['precio_base'];
    $incremento = (float)$detalle['incremento_porcentaje'];
    $precio_aplicado = $precio_base + ($precio_base * ($incremento / 100));
    $total_pagado = (float)$detalle['total_pagado'];
    $saldo_pendiente = (float)$detalle['total'] - $total_pagado;
    $deposito_minimo = (float)$detalle['total'] * 0.30;
    $cumple_deposito = $total_pagado >= $deposito_minimo;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<meta charset="UTF-8">
<title>Registrar Pago</title>
<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background-color: #173248;
    background-image:
        linear-gradient(rgba(13, 36, 52, 0.34), rgba(13, 36, 52, 0.48)),
        url("img/fondos/fondo-1.jpg");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #263238;
    animation: fondos-turisticos 42s infinite;
}

.contenedor {
    width: 92%;
    max-width: 1180px;
    margin: 30px auto;
}

.encabezado {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

h1, h2, h3 {
    margin: 0;
}

h1 {
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(13, 36, 52, 0.42);
}

h2 {
    font-size: 20px;
    color: #1f3b57;
    margin-bottom: 18px;
}

h3 {
    font-size: 17px;
    color: #6b1f1f;
    margin-bottom: 8px;
}

.boton, button {
    display: inline-block;
    border: none;
    border-radius: 6px;
    padding: 10px 16px;
    background: #2f6f9f;
    color: #fff;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
}

.boton-secundario {
    background: #607d8b;
}

.panel {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(255, 255, 255, 0.48);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 24px rgba(13, 36, 52, 0.18);
}

.selector {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    align-items: end;
}

label {
    display: block;
    margin-bottom: 6px;
    color: #425466;
    font-weight: bold;
}

select, input {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #c8d6e3;
    border-radius: 6px;
    padding: 10px;
    background: #fff;
    font-size: 14px;
}

.grid-info {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.dato {
    border: 1px solid #e1e9f0;
    border-radius: 6px;
    padding: 12px;
    background: #fafcff;
}

.dato span {
    display: block;
    color: #607d8b;
    font-size: 12px;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.dato strong {
    display: block;
    color: #263238;
    font-size: 15px;
}

.saldo strong {
    color: #b23b3b;
}

.pagado strong {
    color: #2e7d32;
}

.alerta-presidencial {
    border: 1px solid #f0c6a5;
    background: #fff6ed;
    color: #5d3420;
}

.alerta-presidencial .estado-deposito {
    display: inline-block;
    margin-top: 8px;
    border-radius: 20px;
    padding: 7px 12px;
    font-weight: bold;
}

.no-cumple {
    background: #fdecea;
    color: #b71c1c;
}

.cumple {
    background: #e8f5e9;
    color: #1b5e20;
}

.formulario-pago {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.campo-completo {
    grid-column: 1 / -1;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.95);
}

th {
    background: #eaf1f8;
    color: #1f3b57;
    text-align: left;
    padding: 11px;
    border-bottom: 1px solid #d7e2ec;
}

td {
    padding: 10px 11px;
    border-bottom: 1px solid #edf2f7;
}

tr:hover td {
    background: #f9fbfd;
}

.vacio {
    padding: 18px;
    text-align: center;
    color: #607d8b;
}

@media (max-width: 850px) {
    .encabezado,
    .selector {
        grid-template-columns: 1fr;
        display: grid;
    }

    .grid-info,
    .formulario-pago {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<div class="contenedor">

<div class="encabezado">
    <h1>Registrar Pago</h1>
    <a class="boton boton-secundario" href="pagos.php">Volver a pagos</a>
</div>

<div class="panel">
    <form class="selector" method="GET">
        <div>
            <label for="id_reservacion">Reservación</label>
            <select name="id_reservacion" id="id_reservacion" onchange="this.form.submit()">
                <?php foreach($lista_reservaciones as $r): ?>
                    <?php
                    $saldo_lista = (float)$r['total'] - (float)$r['total_pagado'];
                    ?>
                    <option value="<?php echo h($r['id_reservacion']); ?>" <?php if((int)$r['id_reservacion'] === $id_reservacion_seleccionada){ echo 'selected'; } ?>>
                        Reservación #<?php echo h($r['id_reservacion']); ?> -
                        <?php echo h($r['nombre'].' '.$r['apellido']); ?> -
                        Hab. <?php echo h($r['numero']); ?> (<?php echo h($r['tipo_habitacion']); ?>) -
                        Saldo <?php echo h(dinero($saldo_lista)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Consultar</button>
    </form>
</div>

<?php if(!$detalle): ?>

<div class="panel">
    <p class="vacio">No hay reservaciones disponibles para registrar pagos.</p>
</div>

<?php else: ?>

<div class="panel">
    <h2>Información de la Reservación</h2>

    <div class="grid-info">
        <div class="dato">
            <span>Cliente</span>
            <strong><?php echo h($detalle['nombre'].' '.$detalle['apellido']); ?></strong>
        </div>

        <div class="dato">
            <span>Habitación</span>
            <strong><?php echo h($detalle['numero']); ?> / Edificio <?php echo h($detalle['edificio']); ?></strong>
        </div>

        <div class="dato">
            <span>Tipo Habitación</span>
            <strong><?php echo h($detalle['tipo_habitacion']); ?></strong>
        </div>

        <div class="dato">
            <span>Temporada</span>
            <strong><?php echo h($detalle['temporada']); ?></strong>
        </div>

        <div class="dato">
            <span>Porcentaje Temporada</span>
            <strong><?php echo h(number_format((float)$detalle['incremento_porcentaje'], 2)); ?>%</strong>
        </div>

        <div class="dato">
            <span>Precio Base</span>
            <strong><?php echo h(dinero($detalle['precio_base'])); ?></strong>
        </div>

        <div class="dato">
            <span>Noches</span>
            <strong><?php echo h($noches); ?></strong>
        </div>

        <div class="dato">
            <span>Precio Aplicado</span>
            <strong><?php echo h(dinero($precio_aplicado)); ?></strong>
        </div>

        <div class="dato">
            <span>Total Reservación</span>
            <strong><?php echo h(dinero($detalle['total'])); ?></strong>
        </div>

        <div class="dato pagado">
            <span>Total Pagado</span>
            <strong><?php echo h(dinero($total_pagado)); ?></strong>
        </div>

        <div class="dato saldo">
            <span>Saldo Pendiente</span>
            <strong><?php echo h(dinero($saldo_pendiente)); ?></strong>
        </div>

        <div class="dato">
            <span>Estado</span>
            <strong><?php echo h($detalle['estado']); ?></strong>
        </div>
    </div>
</div>

<?php if($detalle['tipo_habitacion']=='Presidencial'): ?>

<div class="panel alerta-presidencial">
    <h3>Suite Presidencial</h3>
    <p><strong>RN-05:</strong> Esta reservación requiere un depósito mínimo del 30%.</p>
    <p><strong>Depósito mínimo requerido:</strong> <?php echo h(dinero($deposito_minimo)); ?></p>
    <p><strong>Monto actual pagado:</strong> <?php echo h(dinero($total_pagado)); ?></p>

    <?php if($cumple_deposito): ?>
        <span class="estado-deposito cumple">✓ Cumple depósito mínimo</span>
    <?php else: ?>
        <span class="estado-deposito no-cumple">✗ No cumple depósito mínimo</span>
    <?php endif; ?>
</div>

<?php endif; ?>

<div class="panel">
    <h2>Nuevo Pago</h2>

    <form class="formulario-pago" method="POST">
        <input type="hidden" name="id_reservacion" value="<?php echo h($detalle['id_reservacion']); ?>">

        <div>
            <label for="monto">Monto</label>
            <input type="number" step="0.01" min="0.01" name="monto" id="monto" required>
        </div>

        <div>
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo">
                <option value="Deposito">Deposito</option>
                <option value="Abono">Abono</option>
                <option value="Total">Total</option>
            </select>
        </div>

        <div class="campo-completo">
            <label for="concepto">Concepto</label>
            <input type="text" name="concepto" id="concepto" required>
        </div>

        <div class="campo-completo">
            <button type="submit">Registrar Pago</button>
        </div>
    </form>
</div>

<div class="panel">
    <h2>Historial de Pagos</h2>

    <table>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Estado</th>
        </tr>

        <?php if(count($historial_pagos)==0): ?>
            <tr>
                <td class="vacio" colspan="5">Esta reservación aún no tiene pagos registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach($historial_pagos as $pago): ?>
                <tr>
                    <td><?php echo h($pago['fecha_pago']); ?></td>
                    <td><?php echo h($pago['tipo']); ?></td>
                    <td><?php echo h($pago['concepto']); ?></td>
                    <td><?php echo h(dinero($pago['monto'])); ?></td>
                    <td><?php echo h($pago['estado']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>

<?php endif; ?>

</div>

</body>
</html>
