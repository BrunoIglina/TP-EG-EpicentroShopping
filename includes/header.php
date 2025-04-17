<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>

<!-- Cargando CSS de Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<header class="p-3 m-0 border-0">
  <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #202833;">
    <div class="container-fluid">
      <div class="logo">
        <a href="index.php">
          <img src="./assets/logo2.png" alt="Epicentro Shopping Logo" class="img-fluid" width="120">
        </a>
      </div>
      
      <!-- Botón de menú para móviles -->
      <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Offcanvas -->
      <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menú</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="locales.php">Promociones</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="mapadesitio.php">Mapa</a>
            </li>

            <?php if ($user_tipo == 'Cliente'): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="novedades.php">Novedades</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="mis_promociones.php">Solicitudes</a>
            </li>
            <?php endif; ?>

            <?php if ($user_tipo == 'Administrador'): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="novedades.php">Novedades</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Gestionar Shopping
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownAdmin">
                <li><a class="dropdown-item" href="admin_locales.php">Gestionar Locales</a></li>
                <li><a class="dropdown-item" href="admin_novedades.php">Gestionar Novedades</a></li>
                <li><a class="dropdown-item" href="admin_promociones.php">Gestionar Promociones</a></li>
                <li><a class="dropdown-item" href="admin_aprobar_clientes.php">Administrar Clientes</a></li>
                <li><a class="dropdown-item" href="admin_aprobar_dueños.php">Gestionar Dueños</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <?php if ($user_tipo == 'Dueno'): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="novedades.php">Novedades</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Gestionar Promociones
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="misPromos.php">Mis Promociones</a></li>
                <li><a class="dropdown-item" href="gestion_promos.php">Administrar Solicitudes</a></li>
              </ul>
            </li>
            <?php endif; ?>
          </ul>
          
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="miperfil.php">Mi Perfil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="logout.php">Cerrar Sesión</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="login.php">Iniciar Sesión</a>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
