<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Registrarse</title>
</head>
<body>
   
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="auth-form">
            <h1>Registrarse</h1>
            <form id="registerForm" method="post">
                
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="tipo">Registrarse como:</label>
                <input type="radio" id="cliente" name="tipo" value="Cliente" required>
                <label for="cliente">Cliente</label>
                <input type="radio" id="dueno" name="tipo" value="Dueño" required>
                <label for="dueno">Dueño</label>

                <button type="submit">Registrarse</button>
            </form>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var tipo = document.querySelector('input[name="tipo"]:checked').value;
            if (tipo === 'Cliente') {
                this.action = '../private/alta_cliente.php';
            } else if (tipo === 'Dueño') {
                this.action = '../private/alta_dueño.php';
            }
            this.submit();
        });
    </script>
</body>
</html>