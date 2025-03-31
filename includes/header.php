<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="__DIR__ ./../css/header.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<title>Bootstrap Example</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<header class="p-3 m-0 border-0 ">
  
<nav class="navbar navbar-expand-lg fixed-top" style="background-color: #202833;">
  <div class="container-fluid">
    <div class="logo">
        <a href="index.php">
            <img src="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/logo2.png" alt="Epicentro Shopping Logo" class="img-fluid" width="120" height="auto">
        </a>
    </div>
    <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="novedades.php">Novedades</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="locales.php">Promociones</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="mapadesitio.php">Mapa</a>
          </li>

          <?php if ($user_tipo == 'Cliente'): ?>
            <li class="nav-item">
              <a class="nav-link" href="mis_promociones.php">Solicitudes</a>
            </li>
          <?php endif; ?>

          <?php if ($user_tipo == 'Administrador'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Gestionar Shopping</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="admin_locales.php">Gestionar Locales</a></li>
              <li><a class="dropdown-item" href="admin_novedades.php">Gestionar Novedades</a></li>
              <li><a class="dropdown-item" href="admin_promociones.php">Gestionar Promociones</a></li>
            </ul>
          </li>

          <?php elseif ($user_tipo == 'Dueno'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Gestionar Promociones</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="misPromos.php">Mis Promociones</a></li>
              <li><a class="dropdown-item" href="gestion_promos.php">Administrar Solicitudes</a></li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="miperfil.php">Mi Perfil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Cerrar Sesión</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Iniciar Sesión</a>
            </li>
          <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

</header>