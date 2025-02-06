<?php
session_start();
require '../env/shopping_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['verification_code'];

    if (isset($_SESSION['verification_code']) && $entered_code == $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']); 
        $_SESSION['code_verified'] = true;
        header('Location: cambiar_contraseña.php');
        exit();
    } else {
        $error = "Código de verificación incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/cod_verif.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Verificar Código</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Verificar Código</h1>
    <form method="POST">
        <label for="verification_code">Código de Verificación:</label>
        <input type="text" id="verification_code" name="verification_code" required>
        <br>
        <button type="submit">Verificar</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>