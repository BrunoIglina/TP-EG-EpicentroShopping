<?php
require_once './includes/navigation_history.php';

$email = isset($_GET['email']) ? $_GET['email'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['verification_code'];

    if (isset($_SESSION['verification_code']) && $entered_code == $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']); 
        $_SESSION['code_verified'] = true;

        header("Location: cambiar_contraseña.php?email=" . urlencode($email));
        exit();
    } else {
        $error = "Código de verificación incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Verificar Código</title>
</head>
<body class="auth-page">
    <div class="wrapper">
            <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>  
        
        <main>
            <div class="auth-container">
                <section class="auth-form">
                    <h2>Verificar Código</h2>

                    <?php if (isset($error)) echo "<p class='text-danger'>" . htmlspecialchars($error) . "</p>"; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="verification_code">Código de Verificación:</label>
                            <input type="text" id="verification_code" name="verification_code" required>
                        </div>
                        <button type="submit" class="btn-primary">Verificar</button>
                    </form>
                </section>
            </div>
        </main>

        <?php include './includes/footer.php'; ?> 
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>