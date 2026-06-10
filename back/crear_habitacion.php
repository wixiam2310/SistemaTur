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

if($_SERVER["REQUEST_METHOD"]=="POST")
{

    $numero = $_POST['numero'];
    $edificio = $_POST['edificio'];
    $tipo = $_POST['tipo'];
    $capacidad = $_POST['capacidad'];
    $precio_base = $_POST['precio_base'];
    $estado = $_POST['estado'];

    $sql = "
    INSERT INTO habitacion
    (
        numero,
        edificio,
        tipo,
        capacidad,
        precio_base,
        estado
    )
    VALUES
    (
        '$numero',
        '$edificio',
        '$tipo',
        $capacidad,
        $precio_base,
        '$estado'
    )
    ";

    if(pg_query($conn,$sql))
    {
        header("Location: habitaciones.php");
        exit();
    }
    else
    {
        echo pg_last_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Crear Habitación</title>
</head>
<body>

<h1>Crear Habitación</h1>

<form method="POST">

Número<br>
<input type="text" name="numero" required>

<br><br>

Edificio<br>
<select name="edificio">

<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>

</select>

<br><br>

Tipo<br>
<select name="tipo">

<option value="Estandar">Estandar</option>
<option value="Doble">Doble</option>
<option value="VistaMar">VistaMar</option>
<option value="JuniorSuite">JuniorSuite</option>
<option value="Ejecutiva">Ejecutiva</option>
<option value="Presidencial">Presidencial</option>

</select>

<br><br>

Capacidad<br>
<input type="number" name="capacidad" min="1" required>

<br><br>

Precio Base<br>
<input type="number" step="0.01" name="precio_base" required>

<br><br>

Estado<br>
<select name="estado">

<option value="Disponible">Disponible</option>
<option value="Reservada">Reservada</option>
<option value="Mantenimiento">Mantenimiento</option>
<option value="Limpieza">Limpieza</option>

</select>

<br><br>

<button type="submit">
Crear Habitación
</button>

</form>

<br>

<a href="habitaciones.php">
Volver
</a>

</body>
</html>