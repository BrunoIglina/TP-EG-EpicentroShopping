<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css"> 
    <title>Epicentro Shopping - Registrarse</title> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/registro.js"></script> 
</head>
<body>
   
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="container mt-5">
            <section class="auth-form mx-auto p-4 border rounded shadow-sm">
                <h1 class="text-center mb-4">Registrarse</h1>
                <form id="registerForm" method="post">
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <div id="emailMessage" class="message text-danger"></div>
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
    <
