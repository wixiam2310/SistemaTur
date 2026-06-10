<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}

$id_ticket = $_GET['id'];

pg_query($conn,"
UPDATE ticket
SET
estado='Cerrado',
fecha_cierre=NOW()
WHERE id_ticket=$id_ticket
");

header("Location: tickets.php");
exit();
?>