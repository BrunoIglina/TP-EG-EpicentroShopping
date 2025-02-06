<?php
session_start();
require '../env/shopping_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/miperfil.css">
    <title>Mi Perfil</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="profile-container">
        <h1>Mi Perfil</h1>
        <!--<p><strong>Nombre:</strong> <//?php  echo htmlspecialchars($user['name']); ?></p> habria que agregar atributo name en bd -->
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <a href="../public/mod_perfil.php">Cambiar Contraseña</a>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>