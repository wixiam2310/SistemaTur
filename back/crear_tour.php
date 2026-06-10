<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

if($_SESSION['rol']!='Encargado Zona Turistica')
{
    die("Acceso denegado");
}

if($_SERVER["REQUEST_METHOD"]=="POST")
{

    $id_actividad = (int)$_POST['id_actividad'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupo_maximo = (int)$_POST['cupo_maximo'];

    /*
    ------------------------------------
    OBTENER TIPO DE ACTIVIDAD
    ------------------------------------
    */

    $sqlActividad = "
    SELECT tipo
    FROM actividad
    WHERE id_actividad = $id_actividad
    ";

    $resultadoActividad = pg_query($conn,$sqlActividad);

    if(pg_num_rows($resultadoActividad)==0)
    {
        die("Actividad no encontrada");
    }

    $actividad = pg_fetch_assoc($resultadoActividad);

    $tipo = trim($actividad['tipo']);

    /*
    ------------------------------------
    BUSCAR GUIA SEGUN ESPECIALIDAD
    ------------------------------------
    */

    if($tipo == 'Acuatica')
    {
        $especialidad = 'Actividades Acuaticas';
    }
    else
    {
        $especialidad = $tipo;
    }

    $sqlGuia = "
    SELECT id_guia
    FROM guia_turistico
    WHERE especialidad = '$especialidad'
    AND estado = 'Disponible'
    ORDER BY id_guia
    LIMIT 1
    ";

    $resultadoGuia = pg_query($conn,$sqlGuia);

    if(!$resultadoGuia)
    {
        die(pg_last_error($conn));
    }

    $guia = pg_fetch_assoc($resultadoGuia);

    if(!$guia)
    {
        die('No existe un guía disponible para la especialidad: '.$especialidad);
    }

    $id_guia = $guia['id_guia'];

    /*
    ------------------------------------
    CREAR TOUR
    ------------------------------------
    */

    $sql = "
    INSERT INTO tour
    (
        id_actividad,
        id_guia,
        nombre,
        fecha,
        hora_inicio,
        hora_fin,
        cupo_maximo,
        cupo_actual,
        estado
    )
    VALUES
    (
        $id_actividad,
        $id_guia,
        '$nombre',
        '$fecha',
        '$hora_inicio',
        '$hora_fin',
        $cupo_maximo,
        0,
        'Programado'
    )
    ";

    if(pg_query($conn,$sql))
    {
        header('Location: tours.php');
        exit();
    }
    else
    {
        echo '<h2>Error al crear tour</h2>';
        echo pg_last_error($conn);
    }

}

$actividades = pg_query($conn,"
SELECT
id_actividad,
nombre
FROM actividad
WHERE estado='Activa'
ORDER BY nombre
");

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Crear Tour</title>
</head>

<body>

<h1>Crear Tour</h1>

<form method="POST">

<label>Actividad:</label>
<br>

<select name="id_actividad" required>

<?php

while($actividad = pg_fetch_assoc($actividades))
{
    echo "
    <option value='".$actividad['id_actividad']."'>
    ".$actividad['nombre']."
    </option>
    ";
}

?>

</select>

<br><br>

<label>Nombre:</label>
<br>
<input type="text" name="nombre" required>

<br><br>

<label>Fecha:</label>
<br>
<input type="date" name="fecha" required>

<br><br>

<label>Hora Inicio:</label>
<br>
<input type="time" name="hora_inicio" required>

<br><br>

<label>Hora Fin:</label>
<br>
<input type="time" name="hora_fin" required>

<br><br>

<label>Cupo Máximo:</label>
<br>
<input
type="number"
name="cupo_maximo"
min="1"
required>

<br><br>

<button type="submit">
Crear Tour
</button>

</form>

<br>

<a href="tours.php">
Volver
</a>

</body>
</html>