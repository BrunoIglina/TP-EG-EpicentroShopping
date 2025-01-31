<?php
session_start();
require '../env/shopping_db.php'; 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['code_verified'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $user_id = $_SESSION['user_id'];

        $update_query = "UPDATE usuarios SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('si', $new_password, $user_id);
        $stmt->execute();

        unset($_SESSION['code_verified']); 
        header('Location: ../public/miperfil.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/cambiar_contraseña.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Cambiar Contraseña</h1>
    <form method="POST">
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <button type="submit">Cambiar Contraseña</button>
    </form>
    <?php include '../includes/footer.php'; ?>
</body>
</html>