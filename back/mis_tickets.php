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

$sql = "
SELECT
id_ticket,
descripcion,
area_afectada,
estado,
prioridad,
fecha_creacion,
fecha_cierre
FROM ticket
WHERE id_usuario=$id_usuario
ORDER BY id_ticket DESC
";

$resultado = pg_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Mis Tickets</title>
</head>
<body>

<h1>Mis Tickets</h1>

<table border="1">

<tr>
<th>ID</th>
<th>Descripción</th>
<th>Área</th>
<th>Estado</th>
<th>Prioridad</th>
<th>Fecha Creación</th>
<th>Fecha Cierre</th>
</tr>

<?php while($fila = pg_fetch_assoc($resultado)){ ?>

<tr>

<td><?php echo $fila['id_ticket']; ?></td>
<td><?php echo $fila['descripcion']; ?></td>
<td><?php echo $fila['area_afectada']; ?></td>
<td><?php echo $fila['estado']; ?></td>
<td><?php echo $fila['prioridad']; ?></td>
<td><?php echo $fila['fecha_creacion']; ?></td>
<td><?php echo $fila['fecha_cierre']; ?></td>

</tr>

<?php } ?>

</table>

<br>

<a href="tickets.php">
Volver
</a>

</body>
</html>