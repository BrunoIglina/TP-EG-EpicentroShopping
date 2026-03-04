<?php
session_start();
require_once __DIR__ . '/email.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../contacto.php");
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$mensaje = trim($_POST['mensaje'] ?? '');

if (empty($nombre) || empty($email) || empty($mensaje)) {
    header("Location: ../../contacto.php?error=2");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../contacto.php?error=3");
    exit();
}

if (enviar_email_contacto($nombre, $email, $mensaje)) {
    header("Location: ../../contacto.php?success=1");
} else {
    header("Location: ../../contacto.php?error=1");
}
exit();