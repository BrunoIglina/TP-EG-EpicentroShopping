<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../scripts/login.js"></script>
    <title>Epicentro Shopping - Iniciar Sesión</title>
</head>
<body>
    <div class="auth-container">
        <section class="auth-form">
            <h1>Iniciar Sesión</h1>
            <form id="loginForm" method="post">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <div id="emailMessage" class="message"></div>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <div id="passwordMessage" class="message" style="color: red;"></div>

                <button type="submit" class="btn btn-login">Ingresar</button>
            </form>
            <a href="registro.php"><button class="btn btn-register">Registrarse</button></a>
            <a href="recuperar_cuenta.php">¿Olvidaste tu contraseña?</a>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>
