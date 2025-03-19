<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include './private/envio_mail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $tipo = trim($_POST['tipo']);

    if (empty($email) || empty($password) || empty($tipo)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: registro.php");
        exit();
    }

    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "El correo electrónico ya está registrado.";
        $stmt->close();
        $conn->close();
        header("Location: registro.php");
        exit();
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));
    $sql = "INSERT INTO usuarios (email, password, tipo, token_validacion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $hashedPassword, $tipo, $token);

    if ($stmt->execute()) {
        if($tipo == "Cliente")
        {
            error_log("📧 Intentando enviar correo a: " . $email . " con token: " . $token);
            sendValidationEmail($email, $token);
            error_log("✅ Función sendValidationEmail ejecutada.");

        }
        $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión. Si deseas ser cliente debes validar tu cuenta con el link que te enviamos a tu correo electrónico.";
    } else {
        $_SESSION['error'] = "Error al registrarse. Inténtalo nuevamente.";
    }

    $stmt->close();
    $conn->close();
    header("Location: registro.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css"> 
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <title>Epicentro Shopping - Registrarse</title> 
</head>
<body>
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
        <main>
            <div class="container mt-5">
                <section class="auth-form mx-auto p-4 border rounded shadow-sm">
                    <h2 class="text-center mb-4">Registrarse</h2>

                    <?php if (isset($_SESSION['error'])): ?>
                        <p class="text-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <p class="text-success text-center"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
                        <meta http-equiv="refresh" content="2;url=login.php">
                    <?php endif; ?>

                    <form action="registro.php" method="post">
                        <div class="form-group">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Registrarse como:</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="cliente" name="tipo" value="Cliente" class="form-check-input" required>
                                <label for="cliente" class="form-check-label">Cliente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="dueno" name="tipo" value="Dueño" class="form-check-input" required>
                                <label for="dueno" class="form-check-label">Dueño</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                    </form>
                </section>
            </div>
        </main>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
