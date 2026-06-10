<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])){
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

$resultado = pg_query($conn,"
SELECT
id_ticket,
descripcion,
area_afectada,
estado,
prioridad,
fecha_creacion,
fecha_cierre
FROM ticket
ORDER BY id_ticket DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Tickets</title>
</head>
<body>

<h1>Tickets</h1>

<p>
<a href="crear_ticket.php">
<button>Crear Ticket</button>
</a>
</p>

<table border="1">

<tr>
<th>ID</th>
<th>Descripción</th>
<th>Área</th>
<th>Estado</th>
<th>Prioridad</th>
<th>Fecha Creación</th>
<th>Acciones</th>
</tr>

<?php while($ticket = pg_fetch_assoc($resultado)){ ?>

<tr>

<td><?php echo $ticket['id_ticket']; ?></td>
<td><?php echo $ticket['descripcion']; ?></td>
<td><?php echo $ticket['area_afectada']; ?></td>
<td><?php echo $ticket['estado']; ?></td>
<td><?php echo $ticket['prioridad']; ?></td>
<td><?php echo $ticket['fecha_creacion']; ?></td>

<td>

<a href="cerrar_ticket.php?id=<?php echo $ticket['id_ticket']; ?>">
Cerrar
</a>

|

<a href="cambiar_prioridad_ticket.php?id=<?php echo $ticket['id_ticket']; ?>">
Prioridad
</a>

</td>

</tr>

<?php } ?>

</table>

<br>

<a href="mis_tickets.php">
<button>Mis Tickets</button>
</a>

<br><br>

<a href="dashboard.php">
Volver
</a>

</body>
</html>