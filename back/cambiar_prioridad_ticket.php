<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}

$id_ticket = $_GET['id'];

if(isset($_POST['guardar']))
{
    $prioridad = $_POST['prioridad'];

    pg_query($conn,"
    UPDATE ticket
    SET prioridad='$prioridad'
    WHERE id_ticket=$id_ticket
    ");

    header("Location: tickets.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Cambiar Prioridad</title>
</head>
<body>

<h1>Cambiar Prioridad</h1>

<form method="POST">

<select name="prioridad">

<option value="Baja">Baja</option>

<option value="Media">Media</option>

<option value="Alta">Alta</option>

</select>

<br><br>

<button type="submit" name="guardar">
Guardar
</button>

</form>

<br>

<a href="tickets.php">
Volver
</a>

</body>
</html>