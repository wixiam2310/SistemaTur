<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['rol']!='Encargado Zona Turistica')
{
    die("Acceso denegado");
}

$id = (int)$_GET['id'];

if(isset($_POST['guardar']))
{

$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$capacidad = $_POST['capacidad_maxima'];
$espacio = $_POST['espacio'];
$estado = $_POST['estado'];

$sql = "
UPDATE evento
SET
nombre='$nombre',
tipo='$tipo',
fecha='$fecha',
hora_inicio='$hora_inicio',
hora_fin='$hora_fin',
capacidad_maxima=$capacidad,
espacio='$espacio',
estado='$estado'
WHERE id_evento=$id
";

pg_query($conn,$sql);

header("Location: eventos.php");
exit();

}

$consulta = pg_query(
$conn,
"
SELECT *
FROM evento
WHERE id_evento=$id
"
);

$evento = pg_fetch_assoc($consulta);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Editar Evento</title>
</head>
<body>

<h1>Editar Evento</h1>

<form method="POST">

Nombre:<br>
<input type="text" name="nombre"
value="<?php echo $evento['nombre']; ?>" required>

<br><br>

Tipo:<br>
<input type="text" name="tipo"
value="<?php echo $evento['tipo']; ?>" required>

<br><br>

Fecha:<br>
<input type="date" name="fecha"
value="<?php echo $evento['fecha']; ?>" required>

<br><br>

Hora Inicio:<br>
<input type="time" name="hora_inicio"
value="<?php echo substr($evento['hora_inicio'],0,5); ?>" required>

<br><br>

Hora Fin:<br>
<input type="time" name="hora_fin"
value="<?php echo substr($evento['hora_fin'],0,5); ?>" required>

<br><br>

Capacidad Máxima:<br>
<input type="number"
name="capacidad_maxima"
value="<?php echo $evento['capacidad_maxima']; ?>"
required>

<br><br>

Espacio:<br>
<input type="text"
name="espacio"
value="<?php echo $evento['espacio']; ?>"
required>

<br><br>

Estado:<br>

<select name="estado">

<option value="Programado"
<?php if($evento['estado']=='Programado') echo 'selected'; ?>>
Programado
</option>

<option value="EnCurso"
<?php if($evento['estado']=='EnCurso') echo 'selected'; ?>>
EnCurso
</option>

<option value="Completado"
<?php if($evento['estado']=='Completado') echo 'selected'; ?>>
Completado
</option>

<option value="Cancelado"
<?php if($evento['estado']=='Cancelado') echo 'selected'; ?>>
Cancelado
</option>

</select>

<br><br>

<button type="submit" name="guardar">
Guardar Cambios
</button>

</form>

<br>

<a href="eventos.php">
Volver
</a>

</body>
</html>