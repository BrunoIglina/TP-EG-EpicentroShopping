<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './config/database.php';
require_once './private/functions/functions_usuarios.php';

$user_id = $_SESSION['user_id'];
$user = get_usuario($user_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Mi Perfil</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <h1 class="text-center my-5">MI PERFIL</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center">
                <?php 
                echo htmlspecialchars($_SESSION['success']); 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        <main class="perfil-container">
            <div class="perfil-card card">
                <div class="card-header">
                    <h2>EMAIL: <?php echo htmlspecialchars($user['email'] ?? 'No disponible'); ?></h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($user) && isset($user['tipo']) && strtolower($user['tipo']) === 'cliente' && isset($user['categoria'])) : ?>
                        <div class="categoria-cliente text-center">
                            <h2>Categoria: 
                            <?php echo htmlspecialchars($user['categoria']); ?>
                            </h2>
                        </div>
                    <?php else: ?>
                        <p class="text-danger"></p>
                    <?php endif; ?>
                    <a href="./mod_perfil.php" class="btn btn-primary mt-3">Cambiar Contraseña</a>
                </div>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>