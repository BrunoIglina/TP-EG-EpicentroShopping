<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Iniciar Sesión</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/login.js"></script> 
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="auth-form">
            <h1>Iniciar Sesión</h1>
            <form id="loginForm" method="post">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <div id="emailMessage" class="message"></div> <!-- Div para mostrar el mensaje del email -->

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <div id="passwordMessage" class="message" style="color: red;"></div><!-- Div para mostrar el mensaje de la contraseña -->

                <button type="submit">Ingresar</button>
            </form>
            <a href="registro.php"><button id="registrarse">Registrarse</button></a>
            <p><a href="recuperar_cuenta.php">¿Olvidaste tu contraseña?</a></p>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
