<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?vista=login");
    exit();
}
// Asumimos que $user ya viene cargado desde el controlador/router
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

    <div class="profile-wrapper py-4">
        <div class="container">
            <div class="row align-items-center mb-4 mt-2">
                <div class="col-2 col-md-1">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>
                <div class="col-8 col-md-10">
                    <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 1.8rem;">
                        Mi Perfil
                    </h2>
                </div>
                <div class="col-2 col-md-1"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">
                    <div class="profile-card shadow border-0">
                        <div class="profile-header text-center py-4 text-white" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                            <div class="profile-avatar mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px; background: white; color: #2575fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold;">
                                <?php echo strtoupper(substr($user['email'], 0, 1)); ?>
                            </div>
                            <div class="profile-role badge rounded-pill bg-white text-primary px-3 py-2 shadow-sm">
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

                        <div class="profile-body p-4 bg-white">
                            <div class="info-section mb-4 border-bottom pb-3">
                                <div class="info-label text-muted small fw-bold text-uppercase">📧 Correo Electrónico</div>
                                <div class="info-value fs-5 fw-semibold"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>

                            <div class="info-section mb-4 border-bottom pb-3">
                                <div class="info-label text-muted small fw-bold text-uppercase">👤 Tipo de Usuario</div>
                                <div class="info-value fs-5"><?php echo htmlspecialchars($user['tipo']); ?></div>
                            </div>

                            <?php if ($user['tipo'] == 'Cliente'): ?>
                                <div class="info-section mb-4 border-bottom pb-3">
                                    <div class="info-label text-muted small fw-bold text-uppercase">⭐ Categoría</div>
                                    <div class="info-value fs-5">
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

                                <div class="stats-grid mb-4">
                                    <div class="stat-card p-3 bg-light rounded-3 text-center border">
                                        <div class="stat-number fw-bold fs-3 text-primary">
                                            <?php echo $total_promos_usadas; ?>
                                        </div>
                                        <div class="stat-label text-muted small text-uppercase">Promociones Usadas</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="action-buttons d-grid gap-2 mt-4">
                                <a href="index.php?vista=cliente_mod_perfil" class="btn btn-dark py-2">
                                    🔒 Cambiar Contraseña
                                </a>

                                <?php if ($user['tipo'] == 'Cliente'): ?>
                                    <a href="index.php?vista=cliente_promociones" class="btn btn-primary py-2">
                                        🎫 Ver Mis Promociones
                                    </a>
                                <?php elseif ($user['tipo'] == 'Dueno'): ?>
                                    <a href="index.php?vista=dueno_promociones" class="btn btn-primary py-2">
                                        🏪 Mis Promociones
                                    </a>
                                    <a href="index.php?vista=dueno_reportes" class="btn btn-outline-secondary py-2">
                                        📊 Ver Reportes
                                    </a>
                                <?php elseif ($user['tipo'] == 'Administrador'): ?>
                                    <a href="index.php?vista=admin_locales" class="btn btn-primary py-2">
                                        🏢 Gestionar Locales
                                    </a>
                                    <a href="index.php?vista=admin_novedades" class="btn btn-primary py-2">
                                        📰 Gestionar Novedades
                                    </a>
                                <?php endif; ?>

                                <a href="logout.php" class="btn btn-outline-danger py-2 mt-2">
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