<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './config/database.php';
require_once './lib/vendor/autoload.php'; 
require_once './private/helpers/email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = getDB();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $user['email']; 
    
    if (enviar_codigo_verificacion($email)) {
        header('Location: cod_verif.php'); 
        exit();
    } else {
        $error = "No se pudo enviar el código. Intente nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    

    <title>Editar Perfil</title>
</head>
<body class="auth-page">
    <div class="wrapper">
            <?php include './includes/header.php'; ?>

        <main>
            <div class="auth-container">
                <section class="form-container">
                    <h1>Editar Perfil</h1>
                    <?php if (isset($error)) echo "<p class='text-danger'>" . htmlspecialchars($error) . "</p>"; ?>
                    <form method="POST">
                        <button type="submit">Enviar Código de Verificación</button>
                    </form>
                </section>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>