<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #202833;">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php?vista=landing">
        <img src="public/assets/logo2.png" alt="Epicentro Shopping Logo" width="120" height="auto">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
        aria-controls="offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel"
        style="background-color: #202833;">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menú</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-center flex-grow-1">
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php?vista=landing">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php?vista=locales">Locales</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php?vista=mapadesitio">Mapa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php?vista=novedades">Novedades</a>
            </li>

            <?php if ($user_tipo == 'Cliente'): ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="index.php?vista=cliente_promociones">Solicitudes</a>
              </li>
            <?php endif; ?>

            <?php if ($user_tipo == 'Administrador'): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  Gestionar Shopping
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                  <li><a class="dropdown-item" href="index.php?vista=admin_locales">Gestionar Locales</a></li>
                  <li><a class="dropdown-item" href="index.php?vista=admin_novedades">Gestionar Novedades</a></li>
                  <li><a class="dropdown-item" href="index.php?vista=admin_promociones">Gestionar Promociones</a></li>
                  <li><a class="dropdown-item" href="index.php?vista=admin_aprobar_clientes">Gestionar Clientes</a></li>
                  <li><a class="dropdown-item" href="index.php?vista=admin_aprobar_duenos">Gestionar Dueños</a></li>
                </ul>
              </li>
            <?php endif; ?>

            <?php if ($user_tipo == 'Dueno'): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  Gestionar Promociones
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                  <li><a class="dropdown-item" href="index.php?vista=dueno_promociones">Mis Promociones</a></li>
                  <li><a class="dropdown-item" href="index.php?vista=dueno_solicitudes">Administrar Solicitudes</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="index.php?vista=dueno_reportes">Reportes</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>

          <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
              <li class="nav-item">
                <a class="nav-link text-white fw-bold" href="index.php?vista=cliente_perfil">Mi Perfil</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-danger fw-bold" href="public/logout.php">Cerrar Sesión</a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="index.php?vista=login">Iniciar Sesión</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>