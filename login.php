<?php
session_start();
require_once './config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: login.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de correo inválido.";
        header("Location: login.php");
        exit();
    }

    $conn = getDB();

    $stmt = $conn->prepare("SELECT id, password, tipo, categoria FROM usuarios WHERE email = ? AND validado = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['user_categoria'] = $user['categoria'];

            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error'] = "No se encontró una cuenta con ese correo electrónico o la cuenta no está validada.";
    }

    $stmt->close();
    header("Location: login.php");
    exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Iniciar Sesión</title>
</head>
<body class="auth-page">
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main>
            <div class="auth-container">
                <section class="auth-form">
                    <h2 style="font-family: 'Poppins', sans-serif;">Iniciar Sesión</h2>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo htmlspecialchars($_SESSION['success']); 
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="post">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>

                        <button type="submit" class="btn btn-login">Ingresar</button>
                    </form>
                    <button class="btn btn-register" onclick="window.location.href='registro.php'">Registrarse</button>
                    <a href="recuperar_cuenta.php">¿Olvidaste tu contraseña?</a>
                </section>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>