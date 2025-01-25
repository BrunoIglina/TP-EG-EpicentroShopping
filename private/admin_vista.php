<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administrador</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Bienvenido, Administrador</h1>
        <nav>
    <ul>
        <li><a href="../public/admin_locales.php">Gestionar Locales</a></li>
        <li><a href="../public/admin_novedades.php">Gestionar Novedades</a></li>
        <li><a href="../public/admin_promociones.php">Gestionar Promociones</a></li>
        <li><a href="../public/admin_aprobar_dueños.php">Aprobar Dueños</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</nav>

    </main>
    <?php include '../includes/footer.php'; ?>
    
    
</body>
</html>