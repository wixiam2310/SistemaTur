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
SELECT id_evento
FROM participacion_evento
WHERE id_participacion = $id
";

$resultado = pg_query($conn,$sql);

$fila = pg_fetch_assoc($resultado);

$id_evento = $fila['id_evento'];

$sql = "
UPDATE participacion_evento
SET estado = 'Cancelado'
WHERE id_participacion = $id
";

pg_query($conn,$sql);

$sql = "
UPDATE evento
SET participantes_actuales =
participantes_actuales - 1
WHERE id_evento = $id_evento
";

pg_query($conn,$sql);

header("Location: mis_eventos.php");

exit();

?>