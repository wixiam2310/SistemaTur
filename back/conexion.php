<?php

$host = "localhost";
$port = "5432";
$dbname = "SistemaTuristico";
$user = "postgres";
$password = "1234";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conn) {
    die("Error de conexión a PostgreSQL");
}

?>