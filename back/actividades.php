<?php

session_start();
include("conexion.php");

$sql = "
SELECT *
FROM actividad
ORDER BY id_actividad
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Actividades</title>
</head>

<body>

<h1>Actividades Turísticas</h1>

<?php

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Playa'
)
{
?>

<p>
<a href="crear_actividad.php">
<button>Crear Actividad</button>
</a>
</p>

<?php
}
?>

<table border="1">

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Tipo</th>
<th>Descripción</th>
<th>Cupo</th>
<th>Precio</th>
<th>Estado</th>
<th>Acción</th>

</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['id_actividad']."</td>";
echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['tipo']."</td>";
echo "<td>".$fila['descripcion']."</td>";
echo "<td>".$fila['cupo_maximo']."</td>";
echo "<td>$".$fila['precio']."</td>";
echo "<td>".$fila['estado']."</td>";

echo "<td>";

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Playa'
)
{

echo "
<a href='editar_actividad.php?id=".$fila['id_actividad']."'>Editar</a>
|
<a href='cancelar_actividad_admin.php?id=".$fila['id_actividad']."'>Cancelar</a>
|
<a href='participantes_actividad.php?id=".$fila['id_actividad']."'>Participantes</a>
";

}
else
{

if($fila['estado']=='Activa')
{

echo "
<a href='inscribir_actividad.php?id=".$fila['id_actividad']."'>
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