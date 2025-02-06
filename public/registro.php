<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Está fallando el enlace o algo , CHEQUEAR -->
    <title>Epicentro Shopping - Registrarse</title> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/registro.js"></script> 
</head>
<body>
   
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="auth-form">
            <h1>Registrarse</h1>
            <form id="registerForm" method="post">
                
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <div id="emailMessage" class="message" style="color: red"></div> <!-- Div para mostrar el mensaje del email -->

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

</body>
</html>
