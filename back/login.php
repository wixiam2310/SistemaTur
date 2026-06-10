<?php

session_start();

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "
SELECT
u.id_usuario,
u.nombre,
u.apellido,
u.email,
r.nombre AS rol
FROM usuario u
JOIN rol r
ON u.id_rol = r.id_rol
WHERE u.email='$email'
AND u.password_hash='$password'
";

$resultado = pg_query($conn,$sql);

if(pg_num_rows($resultado)>0)
{
    $usuario = pg_fetch_assoc($resultado);

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellido'] = $usuario['apellido'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['rol'] = $usuario['rol'];

    header("Location: dashboard.php");
    exit();
}
else
{
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Credenciales incorrectas</title>
</head>
<body>

<h1>Credenciales incorrectas</h1>

<p>
El correo o la contraseña no coinciden con un usuario registrado.
</p>

<p>
<a href="index.html">
Volver al inicio de sesión
</a>
</p>

</body>
</html>
<?php
}
?>
