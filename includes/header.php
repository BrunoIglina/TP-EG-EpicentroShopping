<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>
<link rel="stylesheet" href="__DIR__ ./../css/header.css">
<header class="bg-dark text-white p-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
            <a href="index.php"><img src="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/logo.png" alt="Epicentro Shopping Logo" class="img-fluid" width="120" height="auto"></a>


            </div>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php"><strong>Inicio</strong></a></li>
                        <li class="nav-item"><a class="nav-link" href="novedades.php"><strong>Novedades</strong></a></li>
                        <li class="nav-item"><a class="nav-link" href="locales.php"><strong>Promociones</strong></a></li>
                        <li class="nav-item"><a class="nav-link" href="mapadesitio.php"><strong>Mapa</strong></a></li>

                         <?php if ($user_tipo == 'Cliente'): ?>
                            <li class="nav-item"><a class="nav-link" href="mis_promociones.php"><strong>Solicitudes</strong></a></li>
                        <?php endif; ?>

                        <?php if ($user_tipo == 'Administrador'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Gestion Shopping</strong>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <a class="dropdown-item" href="admin_locales.php"><strong>Gestionar Locales</strong></a>
                                    <a class="dropdown-item" href="admin_novedades.php"><strong>Gestionar Novedades</strong></a>
                                    <a class="dropdown-item" href="admin_promociones.php"><strong>Gestionar Promociones</strong></a>
                                    <a class="dropdown-item" href="admin_aprobar_dueños.php"><strong>Aprobar Dueños</strong></a>
                                </div>
                            </li>
                        <?php elseif ($user_tipo == 'Dueno'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="duenoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Gestionar Promociones</strong>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="duenoDropdown">
                                    <a class="dropdown-item" href="misPromos.php"><strong>Mis Promociones </strong></a>
                                    <a class="dropdown-item" href="gestion_promos.php"><strong>Administrar Solicitudes</strong></a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav mx-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item mi-perfil"><a class="nav-link" href="miperfil.php"><strong>Mi Perfil</strong></a></li>
                            <li class="nav-item cerrar-sesion"><a class="nav-link" href="logout.php"><strong>Cerrar Sesión</strong></a></li>
                        <?php else: ?>
                            <li class="nav-item mi-perfil"><a class="nav-link" href="login.php"><strong>Iniciar Sesión</strong></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {

        $('.dropdown-toggle').dropdown();
    });
</script>
