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
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$cupo_maximo = $_POST['cupo_maximo'];
$estado = $_POST['estado'];

$sql = "
UPDATE tour
SET
nombre='$nombre',
fecha='$fecha',
hora_inicio='$hora_inicio',
hora_fin='$hora_fin',
cupo_maximo=$cupo_maximo,
estado='$estado'
WHERE id_tour=$id
";

pg_query($conn,$sql);

header("Location: tours.php");
exit();

}

$consulta = pg_query(
$conn,
"
SELECT *
FROM tour
WHERE id_tour=$id
"
);

$tour = pg_fetch_assoc($consulta);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Editar Tour</title>
</head>

<body>

<h1>Editar Tour</h1>

<form method="POST">

Nombre:<br>
<input
type="text"
name="nombre"
value="<?php echo $tour['nombre']; ?>"
required>

<br><br>

Fecha:<br>
<input
type="date"
name="fecha"
value="<?php echo $tour['fecha']; ?>"
required>

<br><br>

Hora Inicio:<br>
<input
type="time"
name="hora_inicio"
value="<?php echo substr($tour['hora_inicio'],0,5); ?>"
required>

<br><br>

Hora Fin:<br>
<input
type="time"
name="hora_fin"
value="<?php echo substr($tour['hora_fin'],0,5); ?>"
required>

<br><br>

Cupo Máximo:<br>
<input
type="number"
name="cupo_maximo"
value="<?php echo $tour['cupo_maximo']; ?>"
required>

<br><br>

Estado:<br>

<select name="estado">

<option value="Programado"
<?php if($tour['estado']=='Programado') echo 'selected'; ?>>
Programado
</option>

<option value="Completado"
<?php if($tour['estado']=='Completado') echo 'selected'; ?>>
Completado
</option>

<option value="Cancelado"
<?php if($tour['estado']=='Cancelado') echo 'selected'; ?>>
Cancelado
</option>

</select>

<br><br>

<button type="submit" name="guardar">
Guardar Cambios
</button>

</form>

<br>

<a href="tours.php">
Volver
</a>

</body>
</html>