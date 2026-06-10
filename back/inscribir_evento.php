<?php

session_start();

if(!isset($_SESSION['id_usuario']))
{
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];
$id_evento = (int)$_GET['id'];

include("conexion.php");

/*
------------------------------------
VERIFICAR EVENTO
------------------------------------
*/

$sqlEvento = "
SELECT
id_evento,
capacidad_maxima,
participantes_actuales,
estado
FROM evento
WHERE id_evento = $id_evento
";

$resEvento = pg_query($conn,$sqlEvento);

if(pg_num_rows($resEvento)==0)
{
    die("Evento no encontrado");
}

$evento = pg_fetch_assoc($resEvento);

/*
------------------------------------
VALIDAR ESTADO
------------------------------------
*/

if(
$evento['estado']=='Cancelado'
||
$evento['estado']=='Finalizado'
)
{
    die("Evento no disponible");
}

/*
------------------------------------
VALIDAR CUPO
------------------------------------
*/

if(
$evento['participantes_actuales']
>=
$evento['capacidad_maxima']
)
{
    die("Evento lleno");
}

/*
------------------------------------
VALIDAR DUPLICADO
------------------------------------
*/

$verificar = "
SELECT *
FROM participacion_evento
WHERE id_usuario = $id_usuario
AND id_evento = $id_evento
AND estado='Inscrito'
";

$resultado = pg_query($conn,$verificar);

if(pg_num_rows($resultado)==0)
{

    pg_query(
    $conn,
    "
    INSERT INTO participacion_evento
    (
        id_usuario,
        id_evento,
        fecha_inscripcion,
        estado
    )
    VALUES
    (
        $id_usuario,
        $id_evento,
        NOW(),
        'Inscrito'
    )
    "
    );

    pg_query(
    $conn,
    "
    UPDATE evento
    SET participantes_actuales =
    participantes_actuales + 1
    WHERE id_evento = $id_evento
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
        'Inscripción exitosa a evento',
        'Confirmacion',
        'Enviada'
    )
    "
    );

}

header("Location: mis_eventos.php");
exit();

?>