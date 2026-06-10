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

if(!isset($_GET['id']))
{
    die("Habitación no encontrada");
}

$id_habitacion = $_GET['id'];

if($_SERVER["REQUEST_METHOD"]=="POST")
{

    $numero = $_POST['numero'];
    $edificio = $_POST['edificio'];
    $tipo = $_POST['tipo'];
    $capacidad = $_POST['capacidad'];
    $precio_base = $_POST['precio_base'];
    $estado = $_POST['estado'];

    $sqlActualizar = "
    UPDATE habitacion
    SET
        numero = '$numero',
        edificio = '$edificio',
        tipo = '$tipo',
        capacidad = $capacidad,
        precio_base = $precio_base,
        estado = '$estado'
    WHERE id_habitacion = $id_habitacion
    ";

    if(pg_query($conn,$sqlActualizar))
    {
        header("Location: habitaciones.php");
        exit();
    }
    else
    {
        echo pg_last_error($conn);
    }
}

$sqlHabitacion = "
SELECT *
FROM habitacion
WHERE id_habitacion = $id_habitacion
";

$resultado = pg_query($conn,$sqlHabitacion);

$habitacion = pg_fetch_assoc($resultado);

if(!$habitacion)
{
    die("Habitación no encontrada");
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Editar Habitación</title>
</head>
<body>

<h1>Editar Habitación</h1>

<form method="POST">

Número<br>
<input
type="text"
name="numero"
value="<?php echo $habitacion['numero']; ?>"
required>

<br><br>

Edificio<br>

<select name="edificio">

<option value="A" <?php if($habitacion['edificio']=='A') echo "selected"; ?>>
A
</option>

<option value="B" <?php if($habitacion['edificio']=='B') echo "selected"; ?>>
B
</option>

<option value="C" <?php if($habitacion['edificio']=='C') echo "selected"; ?>>
C
</option>

</select>

<br><br>

Tipo<br>

<select name="tipo">

<option value="Estandar" <?php if($habitacion['tipo']=='Estandar') echo "selected"; ?>>
Estandar
</option>

<option value="Doble" <?php if($habitacion['tipo']=='Doble') echo "selected"; ?>>
Doble
</option>

<option value="VistaMar" <?php if($habitacion['tipo']=='VistaMar') echo "selected"; ?>>
VistaMar
</option>

<option value="JuniorSuite" <?php if($habitacion['tipo']=='JuniorSuite') echo "selected"; ?>>
JuniorSuite
</option>

<option value="Ejecutiva" <?php if($habitacion['tipo']=='Ejecutiva') echo "selected"; ?>>
Ejecutiva
</option>

<option value="Presidencial" <?php if($habitacion['tipo']=='Presidencial') echo "selected"; ?>>
Presidencial
</option>

</select>

<br><br>

Capacidad<br>
<input
type="number"
name="capacidad"
min="1"
value="<?php echo $habitacion['capacidad']; ?>"
required>

<br><br>

Precio Base<br>
<input
type="number"
step="0.01"
name="precio_base"
value="<?php echo $habitacion['precio_base']; ?>"
required>

<br><br>

Estado<br>

<select name="estado">

<option value="Disponible" <?php if($habitacion['estado']=='Disponible') echo "selected"; ?>>
Disponible
</option>

<option value="Reservada" <?php if($habitacion['estado']=='Reservada') echo "selected"; ?>>
Reservada
</option>

<option value="Mantenimiento" <?php if($habitacion['estado']=='Mantenimiento') echo "selected"; ?>>
Mantenimiento
</option>

<option value="Limpieza" <?php if($habitacion['estado']=='Limpieza') echo "selected"; ?>>
Limpieza
</option>

</select>

<br><br>

<button type="submit">
Guardar Cambios
</button>

</form>

<br>

<a href="habitaciones.php">
Volver
</a>

</body>
</html>