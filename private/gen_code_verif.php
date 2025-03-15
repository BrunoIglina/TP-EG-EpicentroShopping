<?php
session_start();
require '../env/shopping_db.php'; 
require '../lib/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generate_verification_code($email) {
    $verification_code = rand(100000, 999999); 

    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'biprueba1@gmail.com'; 
        $mail->Password = 'cvqhwxgolwgjskdt'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@epicentroshopping.com', 'Epicentro Shopping');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Codigo de Verificacion';
        $mail->Body    = "Tu codigo de verificacion es: $verification_code";
        $mail->AltBody = "Tu codigo de verificacion es: $verification_code";

        $mail->send();
        $_SESSION['verification_code'] = $verification_code; 

        return true; 
    } catch (Exception $e) {
        return "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}"; 
    }
}
?>
