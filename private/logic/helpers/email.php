<?php
require_once __DIR__ . '/../../lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_codigo_verificacion($email) {
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

function enviar_email_contacto($nombre, $email, $mensaje) {
    $mail = new PHPMailer(true);
    
    try {
        configurar_smtp($mail);
        
        $mail->setFrom($email, $nombre);
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

function configurar_smtp($mail) {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'biprueba1@gmail.com'; 
    $mail->Password = 'drmcehryxztedefq'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
}