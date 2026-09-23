<?php
/* Cambia estos valores por los datos de tu servidor MySQL. */
$servidor = 'hectorapi.alwaysdata.net';
$usuario = 'roothectorapi';
$contrasena = 'clase1234';
$base_datos = 'sistema_envios';

$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
    die('No fue posible conectar con la base de datos: ' . $conexion->connect_error);
}
?>
