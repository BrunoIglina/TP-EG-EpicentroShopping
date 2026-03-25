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

    <title>Mi Perfil de Usuario | Epicentro Shopping</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="public/css/fix_header.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/buttons.css">
    <link rel="stylesheet" href="public/css/profile.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">

</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main id="main-content" class="profile-wrapper py-4">
        <div class="container">
            <div class="row align-items-center mb-4 mt-2">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>
                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Mi Perfil
                    </h1>
                </div>
                <div class="col-2 col-md-1"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">
                    <div class="profile-card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="profile-header text-center py-5 text-white" style="background: linear-gradient(135deg, #1a2a6c 0%, #2575fc 100%);">
                            <div class="profile-avatar mx-auto mb-3 shadow" style="width: 90px; height: 90px; background: white; color: #1a2a6c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; font-weight: bold;" aria-hidden="true">
                                <?= strtoupper(substr($user['email'], 0, 1)); ?>
                            </div>
                            <div class="profile-role badge rounded-pill px-4 py-2 shadow-sm fw-bold">
                                <?php
                                $role_display = [
                                    'Cliente' => '👤 CLIENTE',
                                    'Dueno' => '🏪 DUEÑO DE LOCAL',
                                    'Administrador' => '⚡ ADMINISTRADOR'
                                ];
                                echo $role_display[$user['tipo']] ?? $user['tipo'];
                                ?>
                            </div>
                        </div>

                        <div class="profile-body p-4 p-md-5 bg-white">
                            <h2 class="visually-hidden">Información de la cuenta</h2>

                            <div class="info-section mb-4 border-bottom pb-3">
                                <div class="info-label small fw-bold text-uppercase mb-1">📧 Correo Electrónico</div>
                                <div class="info-value fs-5 fw-semibold text-break"><?= htmlspecialchars($user['email']); ?></div>
                            </div>

                            <div class="info-section mb-4 border-bottom pb-3">
                                <div class="info-label small fw-bold text-uppercase mb-1">👤 Tipo de Cuenta</div>
                                <div class="info-value fs-5"><?= htmlspecialchars($user['tipo']); ?></div>
                            </div>

                            <?php if ($user['tipo'] == 'Cliente'): ?>
                                <div class="info-section mb-4 border-bottom pb-3">
                                    <div class="info-label small fw-bold text-uppercase mb-1">⭐ Nivel de Socio</div>
                                    <div class="info-value fs-5">
                                        <?php
                                        $cat_icons = ['Inicial' => '🥉', 'Medium' => '🥈', 'Premium' => '🥇'];
                                        echo ($cat_icons[$user['categoria']] ?? '') . ' ' . htmlspecialchars($user['categoria']);
                                        ?>
                                    </div>
                                </div>

                                <div class="stats-grid mb-4">
                                    <div class="stat-card p-3 bg-light rounded-4 text-center border-0 shadow-sm">
                                        <div class="stat-number fw-bold fs-2"><?= $total_promos_usadas; ?></div>
                                        <div class="stat-label text-muted small text-uppercase fw-bold">Promociones Usadas</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="action-buttons d-grid gap-3 mt-5">
                                <a href="index.php?vista=cliente_mod_perfil" class="btn btn-dark py-2 fw-bold shadow-sm">
                                    <i class="bi bi-shield-lock"></i> Cambiar Contraseña
                                </a>

                                <?php if ($user['tipo'] == 'Cliente'): ?>
                                    <a href="index.php?vista=cliente_promociones" class="btn btn-primary py-2 fw-bold shadow-sm">
                                        🎫 Mis Solicitudes
                                    </a>
                                <?php elseif ($user['tipo'] == 'Dueno'): ?>
                                    <a href="index.php?vista=dueno_promociones" class="btn btn-primary py-2 fw-bold shadow-sm">
                                        🏪 Gestionar Mis Promos
                                    </a>
                                <?php elseif ($user['tipo'] == 'Administrador'): ?>
                                    <a href="index.php?vista=admin_locales" class="btn btn-primary py-2 fw-bold shadow-sm">
                                        🏢 Panel Administrativo
                                    </a>
                                <?php endif; ?>

                                <a href="public/logout.php" class="btn btn-outline-danger py-2 mt-2 fw-bold">
                                    Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>