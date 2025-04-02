<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/private/functions_usuarios.php');

$user_id = $_SESSION['user_id'];
$user = get_usuario($user_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/miperfil.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Mi Perfil</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <h2 class="text-center my-5">Mi Perfil</h2>

        <main class="perfil-container">
            <div class="perfil-card card">
                <div class="card-header">
                    <h3>Email: <?php echo htmlspecialchars($user['email'] ?? 'No disponible'); ?></h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($user) && isset($user['tipo']) && strtolower($user['tipo']) === 'cliente' && isset($user['categoria'])) : ?>
                        <div class="categoria-cliente text-center">
                            <p>Categoria: 
                            <?php echo htmlspecialchars($user['categoria']); ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <p class="text-danger">No se encontró categoría para este usuario.</p>
                    <?php endif; ?>
                    <a href="./mod_perfil.php" class="btn btn-primary mt-3">Cambiar Contraseña</a>
                </div>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>