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

/*
------------------------------------
ELIMINAR PARTICIPACIONES
------------------------------------
*/

pg_query(
$conn,
"
DELETE FROM participacion_evento
WHERE id_evento = $id
"
);

/*
------------------------------------
ELIMINAR EVENTO
------------------------------------
*/

pg_query(
$conn,
"
DELETE FROM evento
WHERE id_evento = $id
"
);

header("Location: eventos.php");
exit();

?>