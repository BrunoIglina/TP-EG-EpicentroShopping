<?php
session_start();
require './env/shopping_db.php'; 
require './lib/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function shortenUrl($longUrl) {
    $apiUrl = "http://tinyurl.com/api-create.php?url=" . urlencode($longUrl);
    
    // Realizar la solicitud GET para acortar la URL
    $shortUrl = file_get_contents($apiUrl);
    
    return $shortUrl;
}

function sendValidationEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Configuración de SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'biprueba1@gmail.com'; // Usa una contraseña de aplicación si es necesario
        $mail->Password = 'stjdtyeisegzpqmw'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Generar el enlace de validación largo
        $validationLink = "http://epicentroshopping.infinityfreeapp.com/private/validar_cliente.php?token=$token";
        
        // Acortar la URL
        $shortValidationLink = shortenUrl($validationLink);

        // Configuración del correo
        $mail->setFrom('no-reply@epicentroshopping.com', 'Epicentro Shopping');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Validación de cuenta en Epicentro Shopping';
        $mail->Body    = "Haz clic en el siguiente enlace para validar tu cuenta: <a href='$shortValidationLink'>Validar cuenta</a>";
        $mail->AltBody = "Haz clic en el siguiente enlace para validar tu cuenta: $shortValidationLink";

        // Envío del correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
    }
}
?>
