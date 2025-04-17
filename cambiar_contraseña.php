<?php
session_start();
    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');

$email = isset($_GET['email']) ? $_GET['email'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $update_query = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('si', $new_password, $user_id);
            $stmt->execute();

            unset($_SESSION['code_verified']); 
            header('Location: ../miperfil.php');
            exit();
        } elseif ($email) {
            $update_query = "UPDATE usuarios SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('ss', $new_password, $email);
            $stmt->execute();

            unset($_SESSION['verification_code']);
            header('Location: login.php');
            exit();
        } else {
            $error = "Correo electrónico no proporcionado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/cambiar_contraseña.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <h2 class="text-center my-4">Cambiar Contraseña</h2>
        <form method="POST">
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required>
            <br>
            <?php
                if (isset($error)) {
                    echo "<p class='text-danger'>$error</p>";
                }
            ?>
            <button type="submit">Cambiar Contraseña</button>
        </form>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
