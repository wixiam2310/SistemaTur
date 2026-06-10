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
SELECT *
FROM habitacion
ORDER BY numero
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Habitaciones</title>
</head>

<body>

<h1>Administración de Habitaciones</h1>

<a href="crear_habitacion.php">
<button>Crear Habitación</button>
</a>

<br><br>

<table border="1">

<tr>
<th>Número</th>
<th>Edificio</th>
<th>Tipo</th>
<th>Capacidad</th>
<th>Precio</th>
<th>Estado</th>
<th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['numero']."</td>";
echo "<td>".$fila['edificio']."</td>";
echo "<td>".$fila['tipo']."</td>";
echo "<td>".$fila['capacidad']."</td>";
echo "<td>$".$fila['precio_base']."</td>";
echo "<td>".$fila['estado']."</td>";

echo "
<td>
<a href='editar_habitacion.php?id=".$fila['id_habitacion']."'>
Editar
</a>
</td>
";

echo "</tr>";

}

?>

</table>

<br>

<a href='dashboard.php'>
Volver
</a>

</body>
</html>