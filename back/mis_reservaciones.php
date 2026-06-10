<?php

session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario']))
{
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "
SELECT
r.id_reservacion,
h.numero,
h.tipo,
r.fecha_check_in,
r.fecha_check_out,
r.estado,
r.total,

(
SELECT COALESCE(SUM(p.monto),0)
FROM pago p
WHERE p.id_reservacion = r.id_reservacion
AND p.estado='Valido'
) AS total_pagado

FROM reservacion r

INNER JOIN habitacion h
ON r.id_habitacion = h.id_habitacion

WHERE r.id_usuario = $id_usuario

ORDER BY r.id_reservacion DESC
";

$resultado = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="estilos_sistema.css">
<title>Mis Reservaciones</title>
</head>

<body>

<h1>Mis Reservaciones</h1>

<table border="1">

<tr>

<th>ID</th>
<th>Habitación</th>
<th>Tipo</th>
<th>Check In</th>
<th>Check Out</th>
<th>Estado</th>
<th>Total</th>
<th>Total Pagado</th>
<th>Saldo Pendiente</th>
<th>Acción</th>

</tr>

<?php

while($fila = pg_fetch_assoc($resultado))
{

$saldo =
(float)$fila['total']
-
(float)$fila['total_pagado'];

echo "<tr>";

echo "<td>".$fila['id_reservacion']."</td>";

echo "<td>".$fila['numero']."</td>";

echo "<td>".$fila['tipo']."</td>";

echo "<td>".$fila['fecha_check_in']."</td>";

echo "<td>".$fila['fecha_check_out']."</td>";

echo "<td>".$fila['estado']."</td>";

echo "<td>$".number_format($fila['total'],2)." MXN</td>";

echo "<td>$".number_format($fila['total_pagado'],2)." MXN</td>";

echo "<td>$".number_format($saldo,2)." MXN</td>";

if(
$fila['estado']!='Cancelada'
&&
$fila['estado']!='Completada'
)
{

echo "
<td>
<a href='cancelar_reservacion.php?id=".$fila['id_reservacion']."'>
Cancelar
</a>
</td>
";

}
else
{

echo "<td>".$fila['estado']."</td>";

}

echo "</tr>";

}

?>

</table>

<br>

<a href="dashboard.php">
Volver
</a>

</body>
</html>