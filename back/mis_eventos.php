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
pe.id_participacion,
e.nombre,
e.tipo,
e.fecha,
pe.estado
FROM participacion_evento pe
JOIN evento e
ON pe.id_evento = e.id_evento
WHERE pe.id_usuario = $id_usuario
ORDER BY pe.id_participacion DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Mis Eventos</title>
</head>

<body>

<h1>Mis Eventos</h1>

<table border="1">

<tr>
<th>Evento</th>
<th>Tipo</th>
<th>Fecha</th>
<th>Estado</th>
<th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['tipo']."</td>";
echo "<td>".$fila['fecha']."</td>";
echo "<td>".$fila['estado']."</td>";

if($fila['estado'] != 'Cancelado')
{
echo "<td>
<a href='cancelar_evento.php?id=".$fila['id_participacion']."'>
Cancelar
</a>
</td>";
}
else
{
echo "<td>Cancelado</td>";
}

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