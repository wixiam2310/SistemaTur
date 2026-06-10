<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

if($_SESSION['rol']!='Encargado Playa')
{
    die("Acceso denegado");
}

include("conexion.php");

if(!isset($_GET['id']))
{
    die("Actividad no encontrada");
}

$id = (int)$_GET['id'];

pg_query(
$conn,
"
UPDATE actividad
SET estado='Cancelada'
WHERE id_actividad=$id
"
);

header("Location: actividades.php");
exit();

?>