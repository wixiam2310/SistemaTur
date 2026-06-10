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

if($_SERVER['REQUEST_METHOD']=='POST')
{

$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$capacidad = $_POST['capacidad_maxima'];
$espacio = $_POST['espacio'];

$sql = "
INSERT INTO evento
(
nombre,
tipo,
fecha,
hora_inicio,
hora_fin,
capacidad_maxima,
participantes_actuales,
espacio,
estado
)
VALUES
(
'$nombre',
'$tipo',
'$fecha',
'$hora_inicio',
'$hora_fin',
$capacidad,
0,
'$espacio',
'Programado'
)
";

pg_query($conn,$sql);

header("Location: eventos.php");
exit();

}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Crear Evento</title>
</head>
<body>

<h1>Crear Evento</h1>

<form method="POST">

Nombre:<br>
<input type="text" name="nombre" required>

<br><br>

Tipo:<br>
<input type="text" name="tipo" required>

<br><br>

Fecha:<br>
<input type="date" name="fecha" required>

<br><br>

Hora Inicio:<br>
<input type="time" name="hora_inicio" required>

<br><br>

Hora Fin:<br>
<input type="time" name="hora_fin" required>

<br><br>

Capacidad Máxima:<br>
<input type="number" name="capacidad_maxima" required>

<br><br>

Espacio:<br>
<input type="text" name="espacio" required>

<br><br>

<button type="submit">
Guardar
</button>

</form>

<br>

<a href="eventos.php">
Volver
</a>

</body>
</html>