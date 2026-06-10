<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

if($_SESSION['rol']!='Turista')
{
    die("Acceso denegado");
}

include("conexion.php");

$sql = "
SELECT *
FROM habitacion
WHERE estado='Disponible'
ORDER BY numero
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Habitaciones Disponibles</title>
</head>

<body>

<h1>Habitaciones Disponibles</h1>

<table border="1">

<tr>
    <th>Número</th>
    <th>Edificio</th>
    <th>Tipo</th>
    <th>Capacidad</th>
    <th>Precio Base</th>
    <th>Acción</th>
</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{
    echo "<tr>";

    echo "<td>".$fila['numero']."</td>";
    echo "<td>".$fila['edificio']."</td>";
    echo "<td>".$fila['tipo']."</td>";
    echo "<td>".$fila['capacidad']." personas</td>";
    echo "<td>$".number_format($fila['precio_base'],2)." MXN</td>";

    echo "
    <td>
        <a href='reservar_habitacion.php?id=".$fila['id_habitacion']."'>
            Reservar
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