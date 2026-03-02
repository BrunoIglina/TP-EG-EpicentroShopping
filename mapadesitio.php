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
        <h2 class="text-center my-4">MAPA DEL SITIO</h2>
        <main class="container my-4 mapa">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="row justify-content-center g-4 mb-5">
                    <div class="col-md-6 col-lg-5">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Navegación General</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="index.php" class="text-decoration-none">Inicio</a></li>
                                    <li class="mb-2"><a href="locales.php" class="text-decoration-none">Locales</a></li>
                                    <li class="mb-2"><a href="promociones.php" class="text-decoration-none">Promociones</a></li>
                                    <li class="mb-2"><a href="novedades.php" class="text-decoration-none">Novedades</a></li>
                                    <li class="mb-2"><a href="contacto.php" class="text-decoration-none">Contacto</a></li>
                                    <li class="mb-2"><a href="mapadesitio.php" class="text-decoration-none">Mapa del Sitio</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Cuenta</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="login.php" class="text-decoration-none">Iniciar Sesión</a></li>
                                    <li class="mb-2"><a href="registro.php" class="text-decoration-none">Registrarse</a></li>
                                    <li class="mb-2"><a href="recuperar_cuenta.php" class="text-decoration-none">Recuperar Cuenta</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="row justify-content-center g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Navegación</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="index.php" class="text-decoration-none">Inicio</a></li>
                                    <li class="mb-2"><a href="locales.php" class="text-decoration-none">Locales</a></li>
                                    <li class="mb-2"><a href="promociones.php" class="text-decoration-none">Promociones</a></li>
                                    <li class="mb-2"><a href="novedades.php" class="text-decoration-none">Novedades</a></li>
                                    <li class="mb-2"><a href="contacto.php" class="text-decoration-none">Contacto</a></li>
                                    <li class="mb-2"><a href="mapadesitio.php" class="text-decoration-none">Mapa del Sitio</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <?php if ($user_tipo == 'Administrador'): ?>
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">Panel de Administración</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="admin_locales.php" class="text-decoration-none">Gestionar Locales</a></li>
                                        <li class="mb-2"><a href="admin_novedades.php" class="text-decoration-none">Gestionar Novedades</a></li>
                                        <li class="mb-2"><a href="admin_promociones.php" class="text-decoration-none">Gestionar Promociones</a></li>
                                        <li class="mb-2"><a href="admin_aprobar_dueños.php" class="text-decoration-none">Aprobar Dueños</a></li>
                                        <li class="mb-2"><a href="admin_aprobar_clientes.php" class="text-decoration-none">Aprobar Clientes</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php elseif ($user_tipo == 'Dueno'): ?>
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Panel de Dueño</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="agregar_local.php" class="text-decoration-none">Agregar Local</a></li>
                                        <li class="mb-2"><a href="misPromos.php" class="text-decoration-none">Mis Promociones</a></li>
                                        <li class="mb-2"><a href="darAltaPromos.php" class="text-decoration-none">Crear Promoción</a></li>
                                        <li class="mb-2"><a href="gestion_promos.php" class="text-decoration-none">Gestionar Solicitudes</a></li>
                                        <li class="mb-2"><a href="reportesDueño.php" class="text-decoration-none">Reportes</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php elseif ($user_tipo == 'Cliente'): ?>
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Panel de Cliente</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="pedir_promocion.php" class="text-decoration-none">Pedir Promoción</a></li>
                                        <li class="mb-2"><a href="mis_promociones.php" class="text-decoration-none">Mis Solicitudes</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Mi Cuenta</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="miperfil.php" class="text-decoration-none">Mi Perfil</a></li>
                                    <li class="mb-2"><a href="mod_perfil.php" class="text-decoration-none">Modificar Perfil</a></li>
                                    <li class="mb-2"><a href="cambiar_contraseña.php" class="text-decoration-none">Cambiar Contraseña</a></li>
                                    <li class="mb-2"><a href="logout.php" class="text-decoration-none text-danger">Cerrar Sesión</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center mb-4">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Ubicación - Epicentro Shopping</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="map-container">
                                <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-60.62850773334504%2C-32.960718554326746%2C-60.62496721744538%2C-32.95877410891247&amp;layer=mapnik&amp;marker=-32.95974633696806%2C-60.6267374753952" style="border: 0; width: 100%; height: 400px;"></iframe>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <small><a href="https://www.openstreetmap.org/?mlat=-32.959746&amp;mlon=-60.626737#map=19/-32.959746/-60.626737" target="_blank" class="text-decoration-none">Ver el mapa más grande</a></small>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <?php include './includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>