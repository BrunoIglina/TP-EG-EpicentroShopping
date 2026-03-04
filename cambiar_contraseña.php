<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';
require_once './config/database.php';
$conn = getDB();

$email = isset($_GET['email']) ? $_GET['email'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_password'])) {
        $new_password_raw = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($new_password_raw !== $confirm_password) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header("Location: cambiar_contraseña.php?email=" . urlencode($email));
            exit();
        }

        $new_password = password_hash($new_password_raw, PASSWORD_BCRYPT);
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $new_password, $user_id);
            $stmt->execute();
            $stmt->close();

            unset($_SESSION['code_verified']);
            $_SESSION['success'] = "Contraseña cambiada exitosamente.";
            header('Location: miperfil.php');
            exit();
        } elseif ($email) {
            $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
            $stmt->bind_param('ss', $new_password, $email);
            $stmt->execute();
            $stmt->close();

            unset($_SESSION['verification_code']);
            $_SESSION['success'] = "Contraseña cambiada exitosamente. Por favor, inicia sesión.";
            header('Location: login.php');
            exit();
        } else {
            $error = "Correo electrónico no proporcionado.";
        }
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
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">

    <title>Cambiar Contraseña</title>
</head>

<body class="auth-page">
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>

        <main>
            <div class="auth-container">
                <section class="auth-form">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <h2>Cambiar Contraseña</h2>

                    <div id="passwordMatch" class="mt-2" style="display: none;"></div>

                    <form method="POST" id="passwordForm">
                        <label for="new_password">Nueva Contraseña:</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">

                        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">



                        <button type="submit" id="submitBtn" class="btn-primary" disabled>Cambiar Contraseña</button>
                    </form>
                </section>
            </div>
        </main>

        <?php include './includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMatchDiv = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');

        function validatePasswords() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (newPassword && confirmPassword) {
                if (newPassword === confirmPassword) {
                    passwordMatchDiv.innerHTML = '<div class="alert alert-success mb-0">✓ Las contraseñas coinciden</div>';
                    passwordMatchDiv.style.display = 'block';
                    submitBtn.disabled = false;
                } else {
                    passwordMatchDiv.innerHTML = '<div class="alert alert-danger mb-0">✗ Las contraseñas no coinciden</div>';
                    passwordMatchDiv.style.display = 'block';
                    submitBtn.disabled = true;
                }
            } else {
                passwordMatchDiv.style.display = 'none';
                submitBtn.disabled = true;
            }
        }

        newPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    </script>
</body>

</html>