<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] != 'Encargado Zona Turistica') {
    die("Acceso denegado");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Panel Encargado Zona Turística</title>
</head>
<body>

<h1>Panel Encargado de Zona Turística</h1>

<p><strong>Usuario:</strong> <?php echo $_SESSION['nombre']; ?></p>

<hr>

<h2>Operación Turística</h2>

<p>
    <a href="ver_tickets.php">
        <button>Gestionar Tickets</button>
    </a>
</p>

<p>
    <a href="control_acceso.php">
        <button>Control de Acceso</button>
    </a>
</p>

<p>
    <a href="tours.php">
        <button>Ver Tours</button>
    </a>
</p>

<p>
    <a href="actividades.php">
        <button>Ver Actividades</button>
    </a>
</p>

<p>
    <a href="logout.php">
        <button>Cerrar Sesión</button>
    </a>
</p>

</body>
</html>