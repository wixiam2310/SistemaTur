<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

if($_SESSION['rol']!='Turista')
{
    die("Acceso denegado");
}

include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

if(!isset($_GET['id']))
{
    die("Habitación no válida");
}

$id_habitacion = (int)$_GET['id'];

$sqlHabitacion = "
SELECT *
FROM habitacion
WHERE id_habitacion = $id_habitacion
";

$resHabitacion = pg_query($conn,$sqlHabitacion);

if(pg_num_rows($resHabitacion)==0)
{
    die("Habitación no encontrada");
}

$habitacion = pg_fetch_assoc($resHabitacion);

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $fecha_check_in  = $_POST['fecha_check_in'];
    $fecha_check_out = $_POST['fecha_check_out'];

    if(empty($fecha_check_in) || empty($fecha_check_out))
    {
        die("Debe seleccionar fechas");
    }

    if($fecha_check_out <= $fecha_check_in)
    {
        die("La fecha de salida debe ser mayor");
    }

    $sqlTemporada = "
    SELECT *
    FROM temporada
    WHERE '$fecha_check_in'
    BETWEEN fecha_inicio AND fecha_fin
    LIMIT 1
    ";

    $resTemporada = pg_query($conn,$sqlTemporada);

    if(pg_num_rows($resTemporada)==0)
    {
        die("No existe temporada para esa fecha");
    }

    $temporada = pg_fetch_assoc($resTemporada);

    $id_temporada = $temporada['id_temporada'];
    $incremento   = $temporada['incremento_porcentaje'];

    $precio_base = $habitacion['precio_base'];

    $dias = floor(
        (strtotime($fecha_check_out) - strtotime($fecha_check_in))
        / 86400
    );

    $precio_temporada =
        $precio_base +
        ($precio_base * ($incremento / 100));

    $total = $precio_temporada * $dias;

    $sqlReservacion = "
    INSERT INTO reservacion
    (
        id_usuario,
        id_habitacion,
        id_temporada,
        fecha_check_in,
        fecha_check_out,
        estado,
        total,
        fecha_creacion
    )
    VALUES
    (
        $id_usuario,
        $id_habitacion,
        $id_temporada,
        '$fecha_check_in',
        '$fecha_check_out',
        'Pendiente',
        $total,
        NOW()
    )
    ";

    pg_query($conn,$sqlReservacion);

    pg_query(
    $conn,
    "
    UPDATE habitacion
    SET estado='Reservada'
    WHERE id_habitacion=$id_habitacion
    "
    );

    $nombre_temporada = $temporada['nombre'];

    pg_query(
    $conn,
    "
    INSERT INTO notificacion
    (
        id_usuario,
        mensaje,
        tipo,
        estado
    )
    VALUES
    (
        $id_usuario,
        'Reservación realizada correctamente. Temporada: $nombre_temporada. Total: $$total MXN',
        'Confirmacion',
        'Enviada'
    )
    "
    );

    header("Location: mis_reservaciones.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Reservar Habitación</title>
</head>

<body>

<h1>Reservar Habitación</h1>

<p><b>Número:</b> <?php echo $habitacion['numero']; ?></p>
<p><b>Tipo:</b> <?php echo $habitacion['tipo']; ?></p>
<p><b>Capacidad:</b> <?php echo $habitacion['capacidad']; ?> personas</p>
<p><b>Precio Base:</b> $<?php echo number_format($habitacion['precio_base'],2); ?> MXN</p>

<form method="POST">

<label>Check In:</label>
<br>
<input
type="date"
name="fecha_check_in"
required>
<br><br>

<label>Check Out:</label>
<br>
<input
type="date"
name="fecha_check_out"
required>
<br><br>

<button type="submit">
Confirmar Reservación
</button>

</form>

<br>

<a href="habitaciones_disponibles.php">
Volver
</a>

</body>
</html>