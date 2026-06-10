<?php

session_start();

$conn = pg_connect(
"host=localhost
port=5432
dbname=SistemaTuristico
user=postgres
password=1234"
);

$id = $_GET['id'];

$sql = "
UPDATE participacion_tour
SET estado='Cancelada'
WHERE id_participacion = $id
";

pg_query($conn,$sql);

header("Location: mis_tours.php");
exit();

?>