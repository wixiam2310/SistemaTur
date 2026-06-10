<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if(
$_SESSION['rol']!='Encargado Zona Turistica'
&&
$_SESSION['rol']!='Encargado Playa'
)
{
    die("Acceso denegado");
}

$id = (int)$_GET['id'];

if(isset($_POST['guardar']))
{

    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $cupo_maximo = $_POST['cupo_maximo'];
    $horario = $_POST['horario'];
    $duracion = $_POST['duracion_minutos'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    $sql = "
    UPDATE actividad
    SET
        nombre='$nombre',
        tipo='$tipo',
        descripcion='$descripcion',
        cupo_maximo=$cupo_maximo,
        horario='$horario',
        duracion_minutos=$duracion,
        precio=$precio,
        estado='$estado'
    WHERE id_actividad=$id
    ";

    pg_query($conn,$sql);

    header("Location: actividades.php");
    exit();

}

$consulta = pg_query(
$conn,
"
SELECT *
FROM actividad
WHERE id_actividad=$id
"
);

$actividad = pg_fetch_assoc($consulta);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Editar Actividad</title>
</head>

<body>

<h1>Editar Actividad</h1>

<form method="POST">

Nombre:<br>
<input
type="text"
name="nombre"
value="<?php echo $actividad['nombre']; ?>"
required>

<br><br>

Tipo:<br>
<input
type="text"
name="tipo"
value="<?php echo $actividad['tipo']; ?>"
required>

<br><br>

Descripción:<br>
<textarea name="descripcion"><?php echo $actividad['descripcion']; ?></textarea>

<br><br>

Cupo Máximo:<br>
<input
type="number"
name="cupo_maximo"
value="<?php echo $actividad['cupo_maximo']; ?>"
required>

<br><br>

Horario:<br>
<input
type="time"
name="horario"
value="<?php echo substr($actividad['horario'],0,5); ?>"
required>

<br><br>

Duración (minutos):<br>
<input
type="number"
name="duracion_minutos"
value="<?php echo $actividad['duracion_minutos']; ?>"
required>

<br><br>

Precio:<br>
<input
type="number"
step="0.01"
name="precio"
value="<?php echo $actividad['precio']; ?>"
required>

<br><br>

Estado:<br>

<select name="estado">

<option value="Activa"
<?php if($actividad['estado']=='Activa') echo 'selected'; ?>>
Activa
</option>

<option value="Cancelada"
<?php if($actividad['estado']=='Cancelada') echo 'selected'; ?>>
Cancelada
</option>

<option value="Completada"
<?php if($actividad['estado']=='Completada') echo 'selected'; ?>>
Completada
</option>

</select>

<br><br>

<button type="submit" name="guardar">
Guardar Cambios
</button>

</form>

<br>

<a href="actividades.php">
Volver
</a>

</body>
</html>