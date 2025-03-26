<?php
session_start();
include(__DIR__ . '/../env/shopping_db.php');
include(__DIR__ . '/../lib/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];
    
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
        $mail->Subject = 'Hemos recibido tu consulta';
        $mail->Body    = "Gracias por contactarnos, $nombre. Hemos recibido tu mensaje y pronto nos comunicaremos contigo.";
        $mail->AltBody = "Gracias por contactarnos, $nombre. Hemos recibido tu mensaje y pronto nos comunicaremos contigo.";

        $mail->send();
        
        header("Location: ../contacto.php?success=1");
        exit();
    } catch (Exception $e) {
        header("Location: ../contacto.php?error=1");
        exit();
    }
}
?>
