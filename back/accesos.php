<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

/*
RF-03
RN-06
RT-05

Solo Encargado Zona Turistica
*/

if($_SESSION['rol']!='Encargado Zona Turistica')
{
    die("Acceso denegado");
}

$sql = "
SELECT
a.id_acceso,
u.nombre,
u.apellido,
z.nombre AS zona,
a.autorizado,
a.fecha_acceso
FROM acceso_turistico a

INNER JOIN usuario u
ON a.id_usuario = u.id_usuario

INNER JOIN zona_turistica z
ON a.id_zona = z.id_zona

ORDER BY a.id_acceso DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Control de Acceso Turístico</title>
</head>
<body>

<h1>Control de Acceso Turístico</h1>

<p>
<a href="registrar_acceso.php">
<button>Registrar Acceso</button>
</a>
</p>

<table border="1">

<tr>
<th>ID</th>
<th>Usuario</th>
<th>Zona</th>
<th>Autorizado</th>
<th>Fecha</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['id_acceso']."</td>";

echo "<td>"
.$fila['nombre']
." "
.$fila['apellido']
."</td>";

echo "<td>".$fila['zona']."</td>";

if($fila['autorizado']=='t')
{
    echo "<td>Permitido</td>";
}
else
{
    echo "<td>Denegado</td>";
}

echo "<td>".$fila['fecha_acceso']."</td>";

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