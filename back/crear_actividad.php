<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if(
$_SESSION['rol'] != 'Encargado Zona Turistica'
&&
$_SESSION['rol'] != 'Encargado Playa'
){
    die("Acceso denegado");
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

$nombre = trim($_POST['nombre']);
$tipo = trim($_POST['tipo']);
$descripcion = trim($_POST['descripcion']);
$cupo_maximo = (int)$_POST['cupo_maximo'];
$horario = $_POST['horario'];
$duracion = (int)$_POST['duracion_minutos'];
$precio = (float)$_POST['precio'];

$sql = "
INSERT INTO actividad
(
nombre,
tipo,
descripcion,
cupo_maximo,
cupo_actual,
horario,
duracion_minutos,
precio,
estado
)
VALUES
(
'$nombre',
'$tipo',
'$descripcion',
$cupo_maximo,
0,
'$horario',
$duracion,
$precio,
'Activa'
)
";

$resultado = pg_query($conn,$sql);

if(!$resultado)
{
    echo "<h2>Error al crear actividad</h2>";
    echo "<pre>";
    echo pg_last_error($conn);
    echo "</pre>";
    exit();
}

header("Location: actividades.php");
exit();

}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Crear Actividad</title>
</head>

<body>

<h1>Crear Actividad</h1>

<form method="POST">

Nombre:<br>
<input type="text" name="nombre" required>
<br><br>

Tipo:<br>
<input type="text" name="tipo" required>
<br><br>

Descripción:<br>
<textarea name="descripcion" required></textarea>
<br><br>

Cupo Máximo:<br>
<input type="number" name="cupo_maximo" required>
<br><br>

Horario:<br>
<input type="time" name="horario" required>
<br><br>

Duración Minutos:<br>
<input type="number" name="duracion_minutos" required>
<br><br>

Precio:<br>
<input type="number" step="0.01" name="precio" required>
<br><br>

<button type="submit">
Guardar
</button>

</form>

<br>

<a href="actividades.php">
Volver
</a>

</body>
</html>