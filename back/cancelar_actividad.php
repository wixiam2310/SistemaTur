<?php

session_start();

$id = $_GET['id'];

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

$sql = "
UPDATE participacion_actividad
SET estado = 'Cancelada'
WHERE id_participacion = $id
";

pg_query($conn,$sql);

header("Location: mis_actividades.php");

exit();

?>