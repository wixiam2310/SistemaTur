<?php

session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_actividad = $_GET['id'];

include("conexion.php");

/*
------------------------------------
VERIFICAR ACTIVIDAD
------------------------------------
*/

$sqlActividad = "
SELECT
id_actividad,
cupo_maximo,
cupo_actual,
estado
FROM actividad
WHERE id_actividad = $id_actividad
";

$resActividad = pg_query($conn,$sqlActividad);

if(pg_num_rows($resActividad) == 0)
{
    die("Actividad no encontrada");
}

$actividad = pg_fetch_assoc($resActividad);

/*
------------------------------------
VALIDAR SI ESTÁ CANCELADA
------------------------------------
*/

if($actividad['estado'] == 'Cancelada')
{
    die("La actividad no está disponible");
}

/*
------------------------------------
VALIDAR SI ESTÁ COMPLETADA
------------------------------------
*/

if($actividad['estado'] == 'Completada')
{
    die("La actividad ya fue completada");
}

/*
------------------------------------
VALIDAR CUPO
------------------------------------
*/

if($actividad['cupo_actual'] >= $actividad['cupo_maximo'])
{
    die("Actividad llena");
}

/*
------------------------------------
VALIDAR INSCRIPCIÓN DUPLICADA
------------------------------------
*/

$verificar = "
SELECT *
FROM participacion_actividad
WHERE id_usuario = $id_usuario
AND id_actividad = $id_actividad
AND estado = 'Activa'
";

$resultado = pg_query($conn,$verificar);

if(pg_num_rows($resultado) == 0)
{

    /*
    -----------------------------
    REGISTRAR PARTICIPACIÓN
    -----------------------------
    */

    $sql = "
    INSERT INTO participacion_actividad
    (
        id_usuario,
        id_actividad,
        fecha_registro,
        estado
    )
    VALUES
    (
        $id_usuario,
        $id_actividad,
        NOW(),
        'Activa'
    )
    ";

    pg_query($conn,$sql);

    /*
    -----------------------------
    ACTUALIZAR CUPO
    -----------------------------
    */

    pg_query(
        $conn,
        "
        UPDATE actividad
        SET cupo_actual = cupo_actual + 1
        WHERE id_actividad = $id_actividad
        "
    );

    /*
    -----------------------------
    MARCAR COMPLETADA SI SE LLENÓ
    -----------------------------
    */

    pg_query(
        $conn,
        "
        UPDATE actividad
        SET estado='Completada'
        WHERE id_actividad=$id_actividad
        AND cupo_actual >= cupo_maximo
        "
    );

    /*
    -----------------------------
    NOTIFICACIÓN
    -----------------------------
    */

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
            'Inscripción exitosa a actividad',
            'Confirmacion',
            'Enviada'
        )
        "
    );
}

header("Location: mis_actividades.php");

exit();

?>