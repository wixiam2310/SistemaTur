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

$id = (int)$_GET['id'];

pg_query(
$conn,
"
UPDATE evento
SET estado='Cancelado'
WHERE id_evento=$id
"
);

header("Location: eventos.php");
exit();

?>