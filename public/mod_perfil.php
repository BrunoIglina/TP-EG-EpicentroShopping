<?php
session_start();
require '../env/shopping_db.php'; 
require '../lib/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $user['email']; // El email no se puede cambiar ya que se loguea con ese mail
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
        $mail->Body    = "Tu código de verificación es: $verification_code";
        $mail->AltBody = "Tu código de verificación es: $verification_code";

        $mail->send();
        $_SESSION['verification_code'] = $verification_code;
        header('Location: cod_verif.php');
        exit;
    } catch (Exception $e) {
        $error = "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!--    <link rel="stylesheet" href="../css/styles.css"> -->
    <link rel="stylesheet" href="../css/mod_perfil.css">
    <title>Editar Perfil</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="form-container">
        <h1>Editar Perfil</h1>
        <form method="POST">
            <button type="submit">Enviar Código de Verificación</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>