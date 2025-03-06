<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Recuperar Cuenta</title>
</head>
<body>
    
    <div class="wrapper">
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
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>