<?php
session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
    <title>Dashboard</title>
</head>
<body>

<h1>
Bienvenido
<?php
echo $_SESSION['nombre']." ".$_SESSION['apellido'];
?>
</h1>

<p>
Correo:
<?php echo $_SESSION['email']; ?>
</p>

<p>
Rol:
<?php echo $_SESSION['rol']; ?>
</p>

<hr>

<h2>Menú Principal</h2>

<?php

/*
====================================
TURISTA
====================================
*/

if($_SESSION['rol']=='Turista')
{
?>

<button onclick="location.href='actividades.php'">
Ver Actividades
</button>

<br><br>

<button onclick="location.href='tours.php'">
Ver Tours
</button>

<br><br>

<button onclick="location.href='eventos.php'">
Ver Eventos
</button>

<br><br>

<button onclick="location.href='habitaciones_disponibles.php'">
Reservar Habitación
</button>

<br><br>

<button onclick="location.href='mis_actividades.php'">
Mis Actividades
</button>

<br><br>

<button onclick="location.href='mis_tours.php'">
Mis Tours
</button>

<br><br>

<button onclick="location.href='mis_eventos.php'">
Mis Eventos
</button>

<br><br>

<button onclick="location.href='mis_reservaciones.php'">
Mis Reservaciones
</button>

<br><br>

<button onclick="location.href='notificaciones.php'">
Mis Notificaciones
</button>

<?php
}

/*
====================================
ENCARGADO ZONA TURISTICA
====================================
*/

elseif($_SESSION['rol']=='Encargado Zona Turistica')
{
?>

<button onclick="location.href='habitaciones.php'">
Administrar Habitaciones
</button>

<br><br>

<button onclick="location.href='tours.php'">
Administrar Tours
</button>

<br><br>

<button onclick="location.href='eventos.php'">
Administrar Eventos
</button>

<br><br>

<button onclick="location.href='tickets.php'">
Tickets
</button>

<br><br>

<button onclick="location.href='accesos.php'">
Control de Acceso
</button>

<br><br>

<button onclick="location.href='pagos.php'">
Pagos
</button>

<br><br>

<button onclick="location.href='reservaciones.php'">
Reservaciones
</button>

<br><br>

<button onclick="location.href='notificaciones.php'">
Notificaciones
</button>

<?php
}

/*
====================================
ENCARGADO PLAYA
====================================
*/

elseif($_SESSION['rol']=='Encargado Playa')
{
?>

<button onclick="location.href='actividades.php'">
Administrar Actividades
</button>

<br><br>

<button onclick="location.href='tickets.php'">
Tickets
</button>

<br><br>

<button onclick="location.href='notificaciones.php'">
Notificaciones
</button>

<?php
}

/*
====================================
GUIA TURISTICO
====================================
*/

elseif($_SESSION['rol']=='Guia Turistico')
{
?>

<button onclick="location.href='mis_tours.php'">
Mis Tours
</button>

<br><br>

<button onclick="location.href='notificaciones.php'">
Notificaciones
</button>

<?php
}
?>

<br><br>

<button onclick="location.href='logout.php'">
Cerrar Sesión
</button>

</body>
</html>