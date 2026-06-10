<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if(
$_SESSION['rol'] != 'Encargado Zona Turistica'
&&
$_SESSION['rol'] != 'Encargado Playa'
){
    die("Acceso denegado");
}

$id = (int)$_GET['id'];

$sql = "
SELECT
u.nombre,
u.apellido,
pa.fecha_registro,
pa.estado
FROM participacion_actividad pa
INNER JOIN usuario u
ON pa.id_usuario = u.id_usuario
WHERE pa.id_actividad = $id
AND pa.estado = 'Activa'
ORDER BY pa.fecha_registro DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Participantes</title>
</head>
<body>

<h1>Participantes de la Actividad</h1>

<table border='1'>

<tr>
<th>Nombre</th>
<th>Apellido</th>
<th>Fecha Registro</th>
<th>Estado</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{
    echo "<tr>";

    echo "<td>".$fila['nombre']."</td>";
    echo "<td>".$fila['apellido']."</td>";
    echo "<td>".$fila['fecha_registro']."</td>";
    echo "<td>".$fila['estado']."</td>";

    echo "</tr>";
}

?>

</table>

<br>

<a href='actividades.php'>Volver</a>

</body>
</html>