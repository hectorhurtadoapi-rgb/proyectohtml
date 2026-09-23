<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: INDEX4.php');
    exit;
}

require_once 'conexion.php';

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$destinatario = trim($_POST['destinatario'] ?? '');

if ($nombre === '' || $telefono === '' || $destinatario === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header('Location: INDEX4.php?estado=error');
    exit;
}

$consulta = $conexion->prepare(
    'INSERT INTO envios (nombre, correo, telefono, destinatario) VALUES (?, ?, ?, ?)'
);
$consulta->bind_param('ssss', $nombre, $correo, $telefono, $destinatario);

if ($consulta->execute()) {
    header('Location: INDEX4.php?estado=guardado');
} else {
    header('Location: INDEX4.php?estado=error');
}

$consulta->close();
$conexion->close();
exit;
?>
