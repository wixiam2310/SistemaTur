<?php

session_start();
include("conexion.php");

$sql = "
SELECT *
FROM evento
ORDER BY id_evento
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Eventos</title>
</head>

<body>

<h1>Eventos</h1>

<?php

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Zona Turistica'
)
{
?>

<p>
<a href="crear_evento.php">
<button>Crear Evento</button>
</a>
</p>

<?php
}
?>

<table border="1">

<tr>
<th>Nombre</th>
<th>Tipo</th>
<th>Fecha</th>
<th>Hora Inicio</th>
<th>Hora Fin</th>
<th>Capacidad</th>
<th>Participantes</th>
<th>Espacio</th>
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
echo "<td>".$fila['hora_inicio']."</td>";
echo "<td>".$fila['hora_fin']."</td>";
echo "<td>".$fila['capacidad_maxima']."</td>";
echo "<td>".$fila['participantes_actuales']."</td>";
echo "<td>".$fila['espacio']."</td>";
echo "<td>".$fila['estado']."</td>";

echo "<td>";

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Zona Turistica'
)
{

echo "
<a href='editar_evento.php?id=".$fila['id_evento']."'>
Editar
</a>

|

<a href='cancelar_evento_admin.php?id=".$fila['id_evento']."'>
Cancelar
</a>

|

<a href='eliminar_evento.php?id=".$fila['id_evento']."' onclick=\"return confirm('¿Eliminar este evento?');\">
Eliminar
</a>

|

<a href='participantes_evento.php?id=".$fila['id_evento']."'>
Participantes
</a>
";

}
else
{

if($fila['estado']=='Programado')
{

echo "
<a href='inscribir_evento.php?id=".$fila['id_evento']."'>
Inscribirse
</a>
";

}
else
{

echo $fila['estado'];

}

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