<?php
require_once __DIR__ . '/../../lib/vendor/autoload.php';
require_once __DIR__ . '/email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $ruta_env = __DIR__ . '/../../../.env';
    if (!file_exists($ruta_env)) {
        header("Location: ../../../index.php?vista=contacto&error=1");
        exit;
    }

    $env = parse_ini_file($ruta_env);
    $secret_key = $env['RECAPTCHA_SECRET_KEY'];


    if (empty($_POST['g-recaptcha-response'])) {
        header("Location: ../../../index.php?vista=contacto&error=1");
        exit;
    }

    $recaptcha_response = $_POST['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}";
    $respuesta_google = json_decode(file_get_contents($url));

    if (!$respuesta_google->success) {
        header("Location: ../../../index.php?vista=contacto&error=1");
        exit;
    }

    $nombre = strip_tags(trim($_POST["nombre"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mensaje = strip_tags(trim($_POST["mensaje"]));

    if (empty($nombre) || empty($mensaje) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../../index.php?vista=contacto&error=1");
        exit;
    }

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        configurar_smtp($mail);

        $mail->setFrom('biprueba1@gmail.com', 'Web Contacto - ' . $nombre);
        $mail->addAddress('biprueba1@gmail.com');
        $mail->addReplyTo($email, $nombre);

        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto: $nombre";
        $mail->Body    = "<h3>Has recibido un nuevo mensaje de contacto</h3>
                          <p><strong>Nombre:</strong> $nombre</p>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Mensaje:</strong><br>$mensaje</p>";

        $mail->send();
        header("Location: ../../../index.php?vista=contacto&success=1");
    } catch (Exception $e) {
        header("Location: ../../../index.php?vista=contacto&error=1");
    }
} else {
    header("Location: ../../../index.php?vista=contacto");
}
exit();
