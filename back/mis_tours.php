<?php

session_start();

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

$sql = "
SELECT
    pt.id_participacion,
    t.nombre,
    t.fecha,
    pt.estado
FROM participacion_tour pt
JOIN tour t
ON pt.id_tour = t.id_tour
WHERE pt.id_usuario = ".$_SESSION['id_usuario'];

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Mis Tours</title>
</head>

<body>

<h1>Mis Tours</h1>

<table border="1">

<tr>
<th>Tour</th>
<th>Fecha</th>
<th>Estado</th>
<th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['fecha']."</td>";
echo "<td>".$fila['estado']."</td>";

echo "<td>";

if($fila['estado'] == 'Activa')
{
    echo "<a href='cancelar_tour.php?id=".$fila['id_participacion']."'>
    Cancelar
    </a>";
}
else
{
    echo "Cancelado";
}

echo "</td>";

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