<?php

session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] != 'Encargado Zona Turistica')
{
    die("Acceso denegado");
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $id_usuario = (int)$_POST['id_usuario'];
    $id_zona = (int)$_POST['id_zona'];

    $sql = "
    SELECT
        a.autorizado,
        u.nombre,
        u.apellido,
        z.nombre AS zona
    FROM acceso_turistico a

    INNER JOIN usuario u
        ON a.id_usuario = u.id_usuario

    INNER JOIN zona_turistica z
        ON a.id_zona = z.id_zona

    WHERE a.id_usuario = $id_usuario
    AND a.id_zona = $id_zona

    ORDER BY a.fecha_acceso DESC
    LIMIT 1
    ";

    $resultado = pg_query($conn,$sql);

    if(pg_num_rows($resultado) == 0)
    {
        $mensaje = "No existe registro de acceso para este usuario en la zona seleccionada.";
    }
    else
    {
        $acceso = pg_fetch_assoc($resultado);

        if(
            $acceso['autorizado'] == 't'
            ||
            $acceso['autorizado'] == true
        )
        {
            $mensaje =
            "ACCESO PERMITIDO: "
            .$acceso['nombre']
            ." "
            .$acceso['apellido']
            ." tiene acceso a "
            .$acceso['zona'];
        }
        else
        {
            $mensaje =
            "ACCESO DENEGADO: "
            .$acceso['nombre']
            ." "
            .$acceso['apellido']
            ." NO tiene acceso a "
            .$acceso['zona'];
        }
    }
}

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
<title>Control de Acceso</title>
</head>
<body>

<h1>Control de Acceso Turístico</h1>

<form method="POST">

<label>ID Usuario:</label><br>
<input
type="number"
name="id_usuario"
required>

<br><br>

<label>Zona:</label><br>

<select name="id_zona">

<?php

while($zona = pg_fetch_assoc($zonas))
{

echo "
<option value='".$zona['id_zona']."'>
".$zona['nombre']."
</option>
";

}

?>

</select>

<br><br>

<button type="submit">
Validar Acceso
</button>

</form>

<br>

<?php

if($mensaje != "")
{
    echo "<h3>$mensaje</h3>";
}

?>

<br>

<a href='dashboard.php'>
Volver
</a>

</body>
</html>