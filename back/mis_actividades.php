<?php

session_start();

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

$id_usuario = $_SESSION['id_usuario'];

$sql = "
SELECT
pa.id_participacion,
a.nombre,
a.tipo,
pa.estado
FROM participacion_actividad pa
JOIN actividad a
ON pa.id_actividad = a.id_actividad
WHERE pa.id_usuario = $id_usuario
ORDER BY pa.id_participacion DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Mis Actividades</title>
</head>

<body>

<h1>Mis Actividades</h1>

<table border='1'>

<tr>
<th>Actividad</th>
<th>Tipo</th>
<th>Estado</th>
<th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['tipo']."</td>";
echo "<td>".$fila['estado']."</td>";

if($fila['estado'] != 'Cancelada')
{
echo "<td>
<a href='cancelar_actividad.php?id=".$fila['id_participacion']."'>
Cancelar
</a>
</td>";
}
else
{
echo "<td>Cancelada</td>";
}

echo "</tr>";

}

?>

</table>

<br>

<a href='dashboard.php'>Volver</a>

</body>
</html>