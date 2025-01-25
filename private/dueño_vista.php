<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueño') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Dueño</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Bienvenido, Dueño</h1>
        <!-- Contenido específico para el dueño -->
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>