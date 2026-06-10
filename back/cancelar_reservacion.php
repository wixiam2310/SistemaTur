<?php

session_start();

$conn = pg_connect("
host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234
");

$id_reservacion = $_GET['id'];

$sqlHabitacion = "
SELECT id_habitacion
FROM reservacion
WHERE id_reservacion = $id_reservacion
";

$resHabitacion = pg_query($conn,$sqlHabitacion);

$fila = pg_fetch_assoc($resHabitacion);

$id_habitacion = $fila['id_habitacion'];

pg_query(
$conn,
"
UPDATE reservacion
SET estado='Cancelada'
WHERE id_reservacion=$id_reservacion
"
);

pg_query(
$conn,
"
UPDATE habitacion
SET estado='Disponible'
WHERE id_habitacion=$id_habitacion
"
);

header("Location: mis_reservaciones.php");
exit();

?>