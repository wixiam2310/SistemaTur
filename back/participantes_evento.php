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

$sql = "
SELECT
u.nombre,
u.apellido,
pe.fecha_inscripcion,
pe.estado
FROM participacion_evento pe
INNER JOIN usuario u
ON pe.id_usuario = u.id_usuario
WHERE pe.id_evento = $id
ORDER BY pe.fecha_inscripcion DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Participantes Evento</title>
</head>
<body>

<h1>Participantes del Evento</h1>

<table border="1">

<tr>
<th>Nombre</th>
<th>Apellido</th>
<th>Fecha</th>
<th>Estado</th>
</tr>

<?php

while($fila=pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['apellido']."</td>";
echo "<td>".$fila['fecha_inscripcion']."</td>";
echo "<td>".$fila['estado']."</td>";

echo "</tr>";

}

?>

</table>

<br>

<a href="eventos.php">
Volver
</a>

</body>
</html>