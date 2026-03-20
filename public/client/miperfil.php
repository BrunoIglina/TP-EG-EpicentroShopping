<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?vista=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/buttons.css">
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">

    <title>Epicentro Shopping - Mi Perfil</title>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
    
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
                                            echo $total_promos_usadas;
                                            ?>
                                        </div>
                                        <div class="stat-label">Promociones Usadas</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="action-buttons">
                                <a href="index.php?vista=cliente_mod_perfil" class="btn btn-gradient">
                                    🔒 Cambiar Contraseña
                                </a>
                                
                                <?php if ($user['tipo'] == 'Cliente'): ?>
                                    <a href="index.php?vista=cliente_promociones" class="btn btn-primary">
                                        🎫 Ver Mis Promociones
                                    </a>
                                <?php elseif ($user['tipo'] == 'Dueno'): ?>
                                    <a href="index.php?vista=dueno_promociones" class="btn btn-primary">
                                        🏪 Mis Promociones
                                    </a>
                                    <a href="index.php?vista=dueno_reportes" class="btn btn-secondary">
                                        📊 Ver Reportes
                                    </a>
                                <?php elseif ($user['tipo'] == 'Administrador'): ?>
                                    <a href="index.php?vista=admin_locales" class="btn btn-primary">
                                        🏢 Gestionar Locales
                                    </a>
                                    <a href="index.php?vista=admin_novedades" class="btn btn-primary">
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
    
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>