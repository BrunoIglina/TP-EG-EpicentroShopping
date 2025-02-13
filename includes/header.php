<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>
<link rel="stylesheet" href="../css/header.css">
<header class="bg-dark text-white p-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="../assets/logo.png" alt="Epicentro Shopping Logo" class="img-fluid">
            </div>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="../assets/desplegable.png" alt="imagen desplegable de opciones" class="navbar-toggler-icon-custom">
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="promociones.php">Promociones</a></li>
                        <li class="nav-item"><a class="nav-link" href="novedades.php">Novedades</a></li>
                        <li class="nav-item"><a class="nav-link" href="locales.php">Locales</a></li>
                        <?php if ($user_tipo == 'Administrador'): ?>
                            <li class="nav-item"><a class="nav-link" href="admin_locales.php">Gestionar Locales</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_novedades.php">Gestionar Novedades</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_promociones.php">Gestionar Promociones</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_aprobar_dueños.php">Aprobar Dueños</a></li>
                            <li class="nav-item"><a class="nav-link" href="miperfil.php">Mi Perfil</a></li>
                        <?php elseif ($user_tipo == 'Dueño'): ?>
                            <li class="nav-item"><a class="nav-link" href="misPromos.php">Mis Promociones</a></li>
                            <li class="nav-item"><a class="nav-link" href="gestion_promos.php">Gestionar Promociones</a></li>
                            <li class="nav-item"><a class="nav-link" href="miperfil.php">Mi Perfil</a></li>
                        <?php elseif ($user_tipo == 'Cliente'): ?>
                            <li class="nav-item"><a class="nav-link" href="mis_promociones.php">Mis Promociones</a></li>
                            <li class="nav-item"><a class="nav-link" href="miperfil.php">Mi Perfil</a></li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>