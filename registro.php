<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $tipo = trim($_POST['tipo']);

    if (empty($email) || empty($password) || empty($tipo)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: registro.php");
        exit();
    }

    // Cargar datos como si fueran enviados por POST
    $_POST['email'] = $email;
    $_POST['password'] = $password;

    ob_start(); // Iniciar buffer de salida

    if ($tipo === 'Cliente') {
        include('./private/alta_cliente.php');
    } else {
        include('./private/alta_dueño.php');
    }


    $response = ob_get_clean(); 

    if (stripos($response, "exitoso") !== false) {
        $_SESSION['success'] = $response;
    } else {
        $_SESSION['error'] = $response;
    }
    

    header("Location: registro.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/registro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> 
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Registrarse</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <div class="auth-container">
            <section class="auth-form">
                <h2 class="text-center my-4" style="font-family: 'Poppins', sans-serif;">Registrarse</h2> 
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<p class='text-success text-center'>" . $_SESSION['success'] . "</p>";
                    unset($_SESSION['success']);
                }
                ?>
                <form action="registro.php" method="post">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="" disabled selected>Selecciona un tipo</option> 
                        <option value="Cliente">Cliente</option>
                        <option value="Dueno">Dueño</option>
                    </select>


                    <button type="submit" class="btn btn-register">Registrarse</button>
                </form>
            </section>
        </div>
        <?php include './includes/footer.php'; ?>
    </div>
</body>
</html>
