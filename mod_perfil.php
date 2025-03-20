<?php
session_start();
    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');
require './lib/vendor/autoload.php'; 
require './private/gen_code_verif.php';

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
    $email = $user['email']; 

    
    $result = generate_verification_code($email);
    
    if ($result === true) {
        header('Location: cod_verif.php'); 
        exit();
    } else {
        $error = $result; 
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!--    <link rel="stylesheet" href="../css/styles.css"> -->
    <link rel="stylesheet" href="./css/mod_perfil.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <title>Editar Perfil</title>
</head>
<body>
    <div class="wrapper">

        <?php include './includes/header.php'; ?>
        <main class="form-container">
            <h1 class="text-center my-4">Editar Perfil</h1>
            <form method="POST">
                <button type="submit">Enviar Código de Verificación</button>
            </form>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>