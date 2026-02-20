<?php
require_once './includes/navigation_history.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './config/database.php';
$conn = getDB();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/buttons.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Mi Perfil</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <?php include './includes/back_button.php'; ?>
    
    <div class="profile-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <?php echo strtoupper(substr($user['email'], 0, 1)); ?>
                            </div>
                            <div class="profile-name">Mi Perfil</div>
                            <div class="profile-role">
                                <?php 
                                $role_display = [
                                    'Cliente' => '👤 Cliente',
                                    'Dueno' => '🏪 Dueño de Local',
                                    'Administrador' => '⚡ Administrador'
                                ];
                                echo $role_display[$user['tipo']] ?? $user['tipo'];
                                ?>
                            </div>
                        </div>
                        
                        <div class="profile-body">
                            <div class="info-section">
                                <div class="info-label">📧 Correo Electrónico</div>
                                <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                            
                            <div class="info-section">
                                <div class="info-label">👤 Tipo de Usuario</div>
                                <div class="info-value"><?php echo htmlspecialchars($user['tipo']); ?></div>
                            </div>
                            
                            <?php if ($user['tipo'] == 'Cliente'): ?>
                                <div class="info-section">
                                    <div class="info-label">⭐ Categoría</div>
                                    <div class="info-value">
                                        <?php 
                                        $categoria_icons = [
                                            'Inicial' => '🥉',
                                            'Medium' => '🥈',
                                            'Premium' => '🥇'
                                        ];
                                        echo $categoria_icons[$user['categoria']] ?? '';
                                        echo ' ' . htmlspecialchars($user['categoria']); 
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="stats-grid">
                                    <div class="stat-card">
                                        <div class="stat-number">
                                            <?php 
                                            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM promociones_cliente WHERE idCliente = ?");
                                            $stmt->bind_param('i', $user_id);
                                            $stmt->execute();
                                            $promo_count = $stmt->get_result()->fetch_assoc()['total'];
                                            $stmt->close();
                                            echo $promo_count;
                                            ?>
                                        </div>
                                        <div class="stat-label">Promociones Usadas</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="action-buttons">
                                <a href="mod_perfil.php" class="btn btn-gradient">
                                    🔒 Cambiar Contraseña
                                </a>
                                
                                <?php if ($user['tipo'] == 'Cliente'): ?>
                                    <a href="mis_promociones.php" class="btn btn-primary">
                                        🎫 Ver Mis Promociones
                                    </a>
                                <?php elseif ($user['tipo'] == 'Dueno'): ?>
                                    <a href="misPromos.php" class="btn btn-primary">
                                        🏪 Mis Promociones
                                    </a>
                                    <a href="reportesDueño.php" class="btn btn-secondary">
                                        📊 Ver Reportes
                                    </a>
                                <?php elseif ($user['tipo'] == 'Administrador'): ?>
                                    <a href="admin_locales.php" class="btn btn-primary">
                                        🏢 Gestionar Locales
                                    </a>
                                    <a href="admin_novedades.php" class="btn btn-primary">
                                        📰 Gestionar Novedades
                                    </a>
                                <?php endif; ?>
                                
                                <a href="logout.php" class="btn btn-danger">
                                    🚪 Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include './includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>