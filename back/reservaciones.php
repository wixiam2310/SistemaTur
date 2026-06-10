<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

if($_SESSION['rol']!='Encargado Zona Turistica')
{
    die("Acceso denegado");
}

include("conexion.php");

$sql = "
SELECT
r.id_reservacion,
u.nombre,
u.apellido,
h.numero,
h.tipo,
r.fecha_check_in,
r.fecha_check_out,
r.estado,
r.total,
r.fecha_creacion

FROM reservacion r

INNER JOIN usuario u
ON r.id_usuario = u.id_usuario

INNER JOIN habitacion h
ON r.id_habitacion = h.id_habitacion

ORDER BY r.id_reservacion DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Administración de Reservaciones</title>
</head>

<body>

<h1>Administración de Reservaciones</h1>

<table border="1">

<tr>
<th>ID</th>
<th>Cliente</th>
<th>Habitación</th>
<th>Tipo</th>
<th>Check In</th>
<th>Check Out</th>
<th>Estado</th>
<th>Total</th>
<th>Fecha Creación</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['id_reservacion']."</td>";

echo "<td>".$fila['nombre']." ".$fila['apellido']."</td>";

echo "<td>".$fila['numero']."</td>";

echo "<td>".$fila['tipo']."</td>";

echo "<td>".$fila['fecha_check_in']."</td>";

echo "<td>".$fila['fecha_check_out']."</td>";

echo "<td>".$fila['estado']."</td>";

echo "<td>$".number_format($fila['total'],2)." MXN</td>";

echo "<td>".$fila['fecha_creacion']."</td>";

echo "</tr>";

}

?>

</table>

<br>

<a href="dashboard.php">
Volver
</a>

</body>
</html>