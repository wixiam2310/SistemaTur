<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

/*
RN-10
Finalización automática de tours
*/

$actualizarTours = "
UPDATE tour
SET estado = 'Completado'
WHERE estado <> 'Completado'
AND
(
    fecha < CURRENT_DATE

    OR

    (
        fecha = CURRENT_DATE
        AND hora_fin <= CURRENT_TIME
    )
)
";

pg_query($conn,$actualizarTours);

/*
Listado de tours
*/

$sql = "
SELECT
t.id_tour,
t.nombre,
t.fecha,
t.hora_inicio,
t.hora_fin,
t.cupo_maximo,
t.cupo_actual,
t.estado,
g.especialidad
FROM tour t
JOIN guia_turistico g
ON t.id_guia = g.id_guia
ORDER BY t.id_tour
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Tours</title>
</head>

<body>

<h1>Tours</h1>

<?php

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Zona Turistica'
)
{
?>

<a href="crear_tour.php">
<button>Crear Tour</button>
</a>

<br><br>

<?php
}
?>

<table border="1">

<tr>
    <th>ID</th>
    <th>Tour</th>
    <th>Fecha</th>
    <th>Inicio</th>
    <th>Fin</th>
    <th>Cupo</th>
    <th>Estado</th>
    <th>Guía</th>
    <th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

echo "<tr>";

echo "<td>".$fila['id_tour']."</td>";
echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['fecha']."</td>";
echo "<td>".$fila['hora_inicio']."</td>";
echo "<td>".$fila['hora_fin']."</td>";

echo "<td>"
.$fila['cupo_actual']
." / "
.$fila['cupo_maximo']
."</td>";

echo "<td>".$fila['estado']."</td>";

echo "<td>".$fila['especialidad']."</td>";

echo "<td>";

/*
ENCARGADO ZONA
*/

if(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Encargado Zona Turistica'
)
{

echo "
<a href='editar_tour.php?id=".$fila['id_tour']."'>
Editar
</a>
";

if($fila['estado']!='Completado')
{
    echo "
    |
    <a href='cancelar_tour.php?id=".$fila['id_tour']."'>
    Cancelar
    </a>
    ";
}

}

/*
TURISTA
*/

elseif(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Turista'
)
{

if(
$fila['estado']!='Completado'
&&
$fila['cupo_actual'] < $fila['cupo_maximo']
)
{
    echo "
    <a href='inscribir_tour.php?id=".$fila['id_tour']."'>
    Inscribirse
    </a>
    ";
}
else
{
    echo "No Disponible";
}

}

/*
GUIA
*/

elseif(
isset($_SESSION['rol'])
&&
$_SESSION['rol']=='Guia Turistico'
)
{

echo "Consulta";

}

else
{
    echo "-";
}

echo "</td>";

echo "</tr>";

}

?>

</table>

<br>

<a href="dashboard.php">
Volver al Dashboard
</a>

</body>
</html>