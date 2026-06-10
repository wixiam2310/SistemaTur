<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if(
$_SESSION['rol']!='Encargado Zona Turistica'
&&
$_SESSION['rol']!='Encargado Playa'
){
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $descripcion = trim($_POST['descripcion']);
    $area = trim($_POST['area_afectada']);

    /*
    --------------------------------
    PRIORIDAD AUTOMÁTICA
    --------------------------------
    */

    $area_lower = strtolower($area);

    if(
        strpos($area_lower,'playa') !== false
        ||
        strpos($area_lower,'mar') !== false
        ||
        strpos($area_lower,'acceso') !== false
    )
    {
        $prioridad = 'Alta';
    }
    elseif(
        strpos($area_lower,'alberca') !== false
        ||
        strpos($area_lower,'evento') !== false
        ||
        strpos($area_lower,'tour') !== false
    )
    {
        $prioridad = 'Media';
    }
    else
    {
        $prioridad = 'Baja';
    }

    $sql = "
    INSERT INTO ticket
    (
        id_usuario,
        descripcion,
        area_afectada,
        estado,
        prioridad,
        fecha_creacion
    )
    VALUES
    (
        $id_usuario,
        '$descripcion',
        '$area',
        'Abierto',
        '$prioridad',
        NOW()
    )
    ";

    $resultado = pg_query($conn,$sql);

    if(!$resultado)
    {
        die(pg_last_error($conn));
    }

    header("Location: mis_tickets.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Crear Ticket</title>
</head>
<body>

<h1>Crear Ticket</h1>

<form method="POST">

<label>Descripción:</label><br>
<textarea name="descripcion" required></textarea>

<br><br>

<label>Área afectada:</label><br>

<input
type="text"
name="area_afectada"
required
placeholder="Ejemplo: Playa Norte"
/>

<br><br>

<p>
La prioridad será asignada automáticamente por el sistema.
</p>

<button type="submit">
Crear Ticket
</button>

</form>

<br>

<a href="tickets.php">
Volver
</a>

</body>
</html>
