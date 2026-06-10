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

if($_SERVER['REQUEST_METHOD']=='POST')
{

$id_usuario = (int)$_POST['id_usuario'];
$id_zona = (int)$_POST['id_zona'];

$autorizado = false;

/*
VALIDAR RESERVACION ACTIVA
*/

$reservacion = pg_query(
$conn,
"
SELECT 1
FROM reservacion
WHERE id_usuario = $id_usuario
AND estado = 'Confirmada'
LIMIT 1
"
);

if(pg_num_rows($reservacion)>0)
{
    $autorizado = true;
}

/*
VALIDAR ACTIVIDAD ACTIVA
*/

if(!$autorizado)
{

$actividad = pg_query(
$conn,
"
SELECT 1
FROM participacion_actividad
WHERE id_usuario = $id_usuario
AND estado='Activa'
LIMIT 1
"
);

if(pg_num_rows($actividad)>0)
{
    $autorizado = true;
}

}

/*
VALIDAR TOUR ACTIVO
*/

if(!$autorizado)
{

$tour = pg_query(
$conn,
"
SELECT 1
FROM participacion_tour
WHERE id_usuario = $id_usuario
AND estado='Activa'
LIMIT 1
"
);

if(pg_num_rows($tour)>0)
{
    $autorizado = true;
}

}

/*
VALIDAR EVENTO
*/

if(!$autorizado)
{

$evento = pg_query(
$conn,
"
SELECT 1
FROM participacion_evento
WHERE id_usuario = $id_usuario
LIMIT 1
"
);

if(pg_num_rows($evento)>0)
{
    $autorizado = true;
}

}

$valor_autorizado = $autorizado ? 'true' : 'false';

$sql = "
INSERT INTO acceso_turistico
(
id_usuario,
id_zona,
autorizado
)
VALUES
(
$id_usuario,
$id_zona,
$valor_autorizado
)
";

$resultado = pg_query($conn,$sql);

if(!$resultado)
{
    die(pg_last_error($conn));
}

header("Location: accesos.php");
exit();

}

/*
SOLO TURISTAS
*/

$usuarios = pg_query(
$conn,
"
SELECT
id_usuario,
nombre,
apellido
FROM usuario
WHERE id_rol = 5
ORDER BY nombre
"
);

/*
ZONAS REALES
*/

$zonas = pg_query(
$conn,
"
SELECT
id_zona,
nombre
FROM zona_turistica
ORDER BY nombre
"
);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Registrar Acceso</title>
</head>
<body>

<h1>Registrar Acceso Turístico</h1>

<form method="POST">

Usuario:<br>

<select name="id_usuario">

<?php

while($u = pg_fetch_assoc($usuarios))
{

echo "
<option value='".$u['id_usuario']."'>
".$u['nombre']." ".$u['apellido']."
</option>
";

}

?>

</select>

<br><br>

Zona:<br>

<select name="id_zona">

<?php

while($z = pg_fetch_assoc($zonas))
{

echo "
<option value='".$z['id_zona']."'>
".$z['nombre']."
</option>
";

}

?>

</select>

<br><br>

<button type="submit">
Registrar Acceso
</button>

</form>

<br>

<a href="accesos.php">
Volver
</a>

</body>
</html>