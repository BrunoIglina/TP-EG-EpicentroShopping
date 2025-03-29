<?php
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/../env/shopping_db.php');
require __DIR__ . '/../lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendValidationEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'biprueba1@gmail.com'; 
        $mail->Password = 'stjdtyeisegzpqmw'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        
        $mail->setFrom('no-reply@epicentroshopping.com', 'Epicentro Shopping');
        $mail->addAddress($email);

        
        $mail->isHTML(true);
        $mail->Subject = 'Validacion de cuenta en Epicentro Shopping';
        $mail->Body    = "Haz clic en el siguiente enlace para validar tu cuenta: <a href='http://localhost/tp/private/validar_cliente.php?token=$token'>Validar cuenta</a>";
        $mail->AltBody = "Haz clic en el siguiente enlace para validar tu cuenta: http://localhost/tp/private/validar_cliente.php?token=$token";

        $mail->send();
        echo 'El mensaje ha sido enviado, haz click en el enlace que te hemos enviado a tu correo para validar tu cuenta.';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
    }
}
?>