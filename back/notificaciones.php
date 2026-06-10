<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$sql = "
SELECT
    id_notificacion,
    mensaje,
    tipo,
    estado,
    fecha_envio
FROM notificacion
WHERE id_usuario = $id_usuario
ORDER BY fecha_envio DESC
";

$resultado = pg_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Mis Notificaciones</title>
</head>
<body>

<h1>Mis Notificaciones</h1>

<table border="1">

<tr>
    <th>ID</th>
    <th>Tipo</th>
    <th>Mensaje</th>
    <th>Estado</th>
    <th>Fecha</th>
</tr>

<?php while($fila = pg_fetch_assoc($resultado)) { ?>

<tr>
    <td><?php echo $fila['id_notificacion']; ?></td>
    <td><?php echo $fila['tipo']; ?></td>
    <td><?php echo $fila['mensaje']; ?></td>
    <td><?php echo $fila['estado']; ?></td>
    <td><?php echo $fila['fecha_envio']; ?></td>
</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php">Volver</a>

</body>
</html>