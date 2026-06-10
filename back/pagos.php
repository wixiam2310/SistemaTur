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

$sql = "
SELECT
p.id_pago,
p.id_reservacion,
u.nombre,
u.apellido,
h.numero AS habitacion,
h.tipo AS tipo_habitacion,
t.nombre AS temporada,
r.estado AS estado_reservacion,
r.total,
p.monto,
p.tipo,
p.estado,
p.fecha_pago,
p.concepto,
(
SELECT COALESCE(SUM(p2.monto),0)
FROM pago p2
WHERE p2.id_reservacion = r.id_reservacion
AND p2.estado='Valido'
) AS total_pagado
FROM pago p
INNER JOIN reservacion r
ON p.id_reservacion = r.id_reservacion
INNER JOIN usuario u
ON r.id_usuario = u.id_usuario
INNER JOIN habitacion h
ON r.id_habitacion = h.id_habitacion
INNER JOIN temporada t
ON r.id_temporada = t.id_temporada
ORDER BY p.id_pago DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<meta charset="UTF-8">
<title>Pagos</title>
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
    width: 94%;
    max-width: 1280px;
    margin: 30px auto;
}

.encabezado {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

h1 {
    margin: 0;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(13, 36, 52, 0.42);
}

.acciones {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.boton {
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
    padding: 18px;
    box-shadow: 0 8px 24px rgba(13, 36, 52, 0.18);
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 1120px;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.95);
}

th {
    background: #eaf1f8;
    color: #1f3b57;
    text-align: left;
    padding: 11px;
    border-bottom: 1px solid #d7e2ec;
    white-space: nowrap;
}

td {
    padding: 10px 11px;
    border-bottom: 1px solid #edf2f7;
    vertical-align: top;
}

tr:hover td {
    background: #f9fbfd;
}

.monto {
    white-space: nowrap;
    text-align: right;
}

.saldo-pendiente {
    color: #b23b3b;
    font-weight: bold;
}

.saldo-liquidado {
    color: #2e7d32;
    font-weight: bold;
}

.etiqueta {
    display: inline-block;
    border-radius: 20px;
    padding: 5px 9px;
    font-size: 12px;
    font-weight: bold;
    background: #eef3f7;
    color: #425466;
    white-space: nowrap;
}

.estado-valido {
    background: #e8f5e9;
    color: #1b5e20;
}

.estado-revertido,
.estado-cancelada {
    background: #fdecea;
    color: #b71c1c;
}

.estado-confirmada,
.estado-completada {
    background: #e3f2fd;
    color: #0d47a1;
}

.estado-pendiente {
    background: #fff8e1;
    color: #795548;
}

.vacio {
    padding: 18px;
    text-align: center;
    color: #607d8b;
}

@media (max-width: 850px) {
    .encabezado {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="contenedor">

<div class="encabezado">
    <h1>Gestión de Pagos</h1>

    <div class="acciones">
        <a class="boton" href="registrar_pago.php">Registrar Pago</a>
        <a class="boton boton-secundario" href="dashboard.php">Volver</a>
    </div>
</div>

<div class="panel">
    <table>
        <tr>
            <th>ID</th>
            <th>Reservación</th>
            <th>Cliente</th>
            <th>Habitación</th>
            <th>Tipo Habitación</th>
            <th>Temporada</th>
            <th>Estado Reservación</th>
            <th>Total</th>
            <th>Pagado</th>
            <th>Saldo</th>
            <th>Monto Pago</th>
            <th>Tipo Pago</th>
            <th>Estado Pago</th>
            <th>Fecha</th>
            <th>Concepto</th>
        </tr>

        <?php if(pg_num_rows($resultado)==0): ?>
            <tr>
                <td class="vacio" colspan="15">No hay pagos registrados.</td>
            </tr>
        <?php else: ?>
            <?php while($fila = pg_fetch_assoc($resultado)): ?>
                <?php
                $saldo = (float)$fila['total'] - (float)$fila['total_pagado'];
                $clase_saldo = $saldo > 0 ? 'saldo-pendiente' : 'saldo-liquidado';
                $clase_estado_pago = 'estado-'.strtolower($fila['estado']);
                $clase_estado_reservacion = 'estado-'.strtolower($fila['estado_reservacion']);
                ?>
                <tr>
                    <td><?php echo h($fila['id_pago']); ?></td>
                    <td>#<?php echo h($fila['id_reservacion']); ?></td>
                    <td><?php echo h($fila['nombre'].' '.$fila['apellido']); ?></td>
                    <td><?php echo h($fila['habitacion']); ?></td>
                    <td><?php echo h($fila['tipo_habitacion']); ?></td>
                    <td><?php echo h($fila['temporada']); ?></td>
                    <td><span class="etiqueta <?php echo h($clase_estado_reservacion); ?>"><?php echo h($fila['estado_reservacion']); ?></span></td>
                    <td class="monto"><?php echo h(dinero($fila['total'])); ?></td>
                    <td class="monto"><?php echo h(dinero($fila['total_pagado'])); ?></td>
                    <td class="monto <?php echo h($clase_saldo); ?>"><?php echo h(dinero($saldo)); ?></td>
                    <td class="monto"><?php echo h(dinero($fila['monto'])); ?></td>
                    <td><?php echo h($fila['tipo']); ?></td>
                    <td><span class="etiqueta <?php echo h($clase_estado_pago); ?>"><?php echo h($fila['estado']); ?></span></td>
                    <td><?php echo h($fila['fecha_pago']); ?></td>
                    <td><?php echo h($fila['concepto']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</div>

</div>

</body>
</html>
