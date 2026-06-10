<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];
$id_tour = (int)$_GET['id'];

include("conexion.php");

/*
------------------------------------
VERIFICAR TOUR
------------------------------------
*/

$sqlTour = "
SELECT
id_tour,
cupo_maximo,
cupo_actual,
estado
FROM tour
WHERE id_tour = $id_tour
";

$resTour = pg_query($conn,$sqlTour);

if(pg_num_rows($resTour)==0)
{
    die("Tour no encontrado");
}

$tour = pg_fetch_assoc($resTour);

/*
------------------------------------
VALIDAR ESTADO
------------------------------------
*/

if(
$tour['estado']=='Cancelado'
||
$tour['estado']=='Completado'
)
{
    die("Tour no disponible");
}

/*
------------------------------------
VALIDAR CUPO
------------------------------------
*/

if(
$tour['cupo_actual']
>=
$tour['cupo_maximo']
)
{
    die("Tour lleno");
}

/*
------------------------------------
VALIDAR DUPLICADO
------------------------------------
*/

$verificar = "
SELECT *
FROM participacion_tour
WHERE id_usuario = $id_usuario
AND id_tour = $id_tour
AND estado='Activa'
";

$resultado = pg_query($conn,$verificar);

if(pg_num_rows($resultado)==0)
{

    pg_query(
    $conn,
    "
    INSERT INTO participacion_tour
    (
        id_usuario,
        id_tour,
        fecha_registro,
        estado
    )
    VALUES
    (
        $id_usuario,
        $id_tour,
        NOW(),
        'Activa'
    )
    "
    );

    pg_query(
    $conn,
    "
    UPDATE tour
    SET cupo_actual = cupo_actual + 1
    WHERE id_tour = $id_tour
    "
    );

    pg_query(
    $conn,
    "
    UPDATE tour
    SET estado='Completado'
    WHERE id_tour=$id_tour
    AND cupo_actual >= cupo_maximo
    "
    );

    pg_query(
    $conn,
    "
    INSERT INTO notificacion
    (
        id_usuario,
        mensaje,
        tipo,
        estado
    )
    VALUES
    (
        $id_usuario,
        'Inscripción exitosa a tour',
        'Confirmacion',
        'Enviada'
    )
    "
    );

}

header("Location: mis_tours.php");
exit();

?>