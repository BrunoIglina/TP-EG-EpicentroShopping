<?php
session_start();
    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');
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
        if ($tipo == "Cliente") {
            error_log("📧 Intentando enviar correo a: " . $email . " con token: " . $token);
            sendValidationEmail($email, $token);
            error_log("✅ Función sendValidationEmail ejecutada.");
            $_SESSION['success'] = "Registro exitoso. Le hemos enviado un mail de confirmación a su dirección.";
        } elseif ($tipo == "Dueño") {
            $_SESSION['success'] = "Registro exitoso. Espere a ser aceptado.";
        }
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
                        <option value="Cliente">Cliente</option>
                        <option value="Dueño">Dueño</option>
                    </select>

                    <button type="submit" class="btn btn-register">Registrarse</button>
                </form>
            </section>
        </div>
        <?php include './includes/footer.php'; ?>
    </div>
</body>
</html>
