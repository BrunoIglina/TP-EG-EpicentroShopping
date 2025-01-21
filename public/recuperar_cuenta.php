<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Recuperar Cuenta</title>
</head>
<body>
    
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="auth-form">
            <h1>Recuperar Cuenta</h1>
            <form action="recuperar_exito.php" method="post">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit">Enviar Instrucciones</button>
            </form>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>