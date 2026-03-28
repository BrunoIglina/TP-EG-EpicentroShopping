<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_codigo_verificacion($email)
{
    $codigo = rand(100000, 999999);
    $_SESSION['verification_code'] = $codigo;

    $mail = new PHPMailer(true);

    try {
        configurar_smtp($mail);

        $mail->setFrom('noreply@epicentroshopping.com', 'Epicentro Shopping');
        $mail->addAddress($email);
        $mail->Subject = 'Código de Verificación';
        $mail->Body = "Tu código de verificación es: $codigo";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar email: " . $mail->ErrorInfo);
        return false;
    }
}

function enviar_email_contacto($nombre, $email, $mensaje)
{
    $mail = new PHPMailer(true);

    try {
        configurar_smtp($mail);

        $mail->setFrom('noreply@epicentroshopping.com', 'Epicentro Shopping');
        $mail->addReplyTo($email, $nombre);
        $mail->addAddress('admin@epicentroshopping.com');
        $mail->Subject = 'Nuevo mensaje de contacto';
        $mail->Body = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar email: " . $mail->ErrorInfo);
        return false;
    }
}

function configurar_smtp($mail)
{
    $ruta_env = __DIR__ . '/../../../.env';

    if (!file_exists($ruta_env)) {
        error_log("Falta el archivo .env en: " . $ruta_env);
        die("Error de configuración del sistema de correos.");
    }

    $env = parse_ini_file($ruta_env);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $env['SMTP_USER'];
    $mail->Password = $env['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';
}
