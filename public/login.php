<?php
session_start();
include '../env/shopping_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: login.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de correo inválido.";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT id, password, tipo, categoria FROM usuarios WHERE email = ? AND validado = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['user_categoria'] = $user['categoria'];

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error'] = "No se encontró una cuenta con ese correo electrónico o la cuenta no está validada.";
    }

    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <title>Epicentro Shopping - Iniciar Sesión</title>
</head>
<body>
    <div class="wrapper">
        <?php include '../includes/header.php'; ?>
        <div class="auth-container">
            <section class="auth-form">
                <h2>Iniciar Sesión</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger'>" . $_SESSION['error'] . "</p>";
                    unset($_SESSION['error']);
                }
                ?>
                <form action="login.php" method="post">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" class="btn btn-login">Ingresar</button>
                </form>
                <a href="registro.php"><button class="btn btn-register">Registrarse</button></a>
                <a href="recuperar_cuenta.php">¿Olvidaste tu contraseña?</a>
            </section>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
