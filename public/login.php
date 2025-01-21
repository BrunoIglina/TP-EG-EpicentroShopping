<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Iniciar Sesión</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="auth-form">
            <h1>Iniciar Sesión</h1>
            <form action="dashboard.php" method="post">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Ingresar</button>
            </form>
            <a href="registro.php"><button id="registrarse">Registrarse</button></a>
            <p><a href="recuperar_cuenta.php">¿Olvidaste tu contraseña?</a></p>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>