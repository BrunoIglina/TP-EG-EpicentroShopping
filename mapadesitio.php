<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/mapadesitio.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">

    <title>Mapa del Sitio - Epicentro Shopping</title>
</head>
<body>
    <div class="wrapper">
            <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>
    <h2 class="text-center my-2">MAPA DEL SITIO</h2>
    <main class="container my-4 mapa">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <ul class="list-unstyled">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="locales.php">Promociones</a></li>
                <li><a href="novedades.php">Novedades</a></li>
                <li><a href="mapadesitio.php">Mapa De Sitio</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </div>
        
        <?php if ($user_tipo == 'Administrador'): ?>
        <div class="col-md-4">
            <ul class="list-unstyled">
                <li><a href="admin_locales.php">Gestionar Locales</a></li>
                <li><a href="admin_novedades.php">Gestionar Novedades</a></li>
                <li><a href="admin_promociones.php">Gestionar Promociones</a></li>
                <li><a href="admin_aprobar_dueños.php">Aprobar Dueños</a></li>
                <li><a href="miperfil.php">Mi Perfil</a></li>
            </ul>
        </div>
        <?php elseif ($user_tipo == 'Dueño'): ?>
        <div class="col-md-4">
            <ul class="list-unstyled">
                <li><a href="misPromos.php">Mis Promociones</a></li>
                <li><a href="gestion_promos.php">Gestionar Promociones</a></li>
                <li><a href="miperfil.php">Mi Perfil</a></li>
            </ul>
        </div>
        <?php elseif ($user_tipo == 'Cliente'): ?>
        <div class="col-md-4">
            <ul class="list-unstyled">
                <li><a href="mis_promociones.php">Solicitudes</a></li>
                <li><a href="miperfil.php">Mi Perfil</a></li>
            </ul>
        </div>
        <?php endif; ?>

        <div class="col-md-4">
            <ul class="list-unstyled">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                <?php endif; ?>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </div>
    </div>

    <div class="map-container">
            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-60.62850773334504%2C-32.960718554326746%2C-60.62496721744538%2C-32.95877410891247&amp;layer=mapnik&amp;marker=-32.95974633696806%2C-60.6267374753952"></iframe><br/>
            <small><a href="https://www.openstreetmap.org/?mlat=-32.959746&amp;mlon=-60.626737#map=19/-32.959746/-60.626737">Ver el mapa más grande</a></small>
    </div>

    </div>
        <?php include './includes/footer.php'; ?>
</main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>