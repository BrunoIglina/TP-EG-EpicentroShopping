<?php
// Mantenemos la seguridad y navegación
require_once __DIR__ . '/../includes/navigation_history.php';
require_once __DIR__ . '/../includes/security_headers.php';

// Detectamos el tipo de usuario para mostrar u ocultar secciones
$user_tipo = $_SESSION['user_tipo'] ?? 'Visitante';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>Mapa del Sitio | Epicentro Shopping</title>
    
    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/mapadesitio.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">

    <style>
        /* Ajuste para que las tarjetas del mapa se vean uniformes */
        .mapa .card { border-radius: 15px; border: none; overflow: hidden; }
        .mapa .card-header { font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .mapa .list-unstyled li a { color: #4e5d6c; transition: color 0.2s; }
        .mapa .list-unstyled li a:hover { color: #0d6efd; text-decoration: underline !important; }
    </style>
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <div class="wrapper">
        <?php include __DIR__ . '/../includes/header.php'; ?>

        <main id="main-content" class="container py-4 mapa">
            
            <div class="row align-items-center mb-5 mt-2">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Mapa de Sitio
                    </h1>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <div class="row justify-content-center g-4">
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h2 class="h6 mb-0"><i class="bi bi-compass"></i> Navegación General</h2>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3"><a href="index.php?vista=landing" class="text-decoration-none">Inicio / Home</a></li>
                                <li class="mb-3"><a href="index.php?vista=locales" class="text-decoration-none">Listado de Locales</a></li>
                                <li class="mb-3"><a href="index.php?vista=novedades" class="text-decoration-none">Novedades del Shopping</a></li>
                                <li class="mb-3"><a href="index.php?vista=contacto" class="text-decoration-none">Contacto</a></li>
                                <li><a href="index.php?vista=mapadesitio" class="text-decoration-none fw-bold">Mapa del Sitio</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h2 class="h6 mb-0"><i class="bi bi-person-circle"></i> Mi Cuenta</h2>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <?php if (!isset($_SESSION['user_id'])): ?>
                                    <li class="mb-3"><a href="index.php?vista=login" class="text-decoration-none">Iniciar Sesión</a></li>
                                    <li class="mb-3"><a href="index.php?vista=registro" class="text-decoration-none">Crear Cuenta</a></li>
                                    <li><a href="index.php?vista=recuperar" class="text-decoration-none">Recuperar Contraseña</a></li>
                                <?php else: ?>
                                    <li class="mb-3"><a href="index.php?vista=cliente_perfil" class="text-decoration-none">Mi Perfil</a></li>
                                    <li class="mb-3"><a href="index.php?vista=cliente_mod_perfil" class="text-decoration-none">Editar mis Datos</a></li>
                                    <?php if ($user_tipo == 'Cliente'): ?>
                                        <li class="mb-3"><a href="index.php?vista=cliente_promociones" class="text-decoration-none">Mis Promociones Pedidas</a></li>
                                    <?php endif; ?>
                                    <li><a href="public/logout.php" class="text-decoration-none text-danger">Cerrar Sesión</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if ($user_tipo == 'Administrador' || $user_tipo == 'Dueno'): ?>
                <div class="col-md-12 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-dark text-white py-3">
                            <h2 class="h6 mb-0"><i class="bi bi-shield-lock"></i> Gestión de Contenidos</h2>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <?php if ($user_tipo == 'Administrador'): ?>
                                    <li class="mb-3"><a href="index.php?vista=admin_locales" class="text-decoration-none">Gestionar Locales</a></li>
                                    <li class="mb-3"><a href="index.php?vista=admin_novedades" class="text-decoration-none">Gestionar Novedades</a></li>
                                    <li class="mb-3"><a href="index.php?vista=admin_promociones" class="text-decoration-none">Aprobar Promociones</a></li>
                                    <li class="mb-3"><a href="index.php?vista=admin_aprobar_duenos" class="text-decoration-none">Validar Dueños</a></li>
                                    <li><a href="index.php?vista=admin_aprobar_clientes" class="text-decoration-none">Validar Clientes</a></li>
                                <?php elseif ($user_tipo == 'Dueno'): ?>
                                    <li class="mb-3"><a href="index.php?vista=dueno_promociones" class="text-decoration-none">Mis Promociones</a></li>
                                    <li class="mb-3"><a href="index.php?vista=dueno_solicitudes" class="text-decoration-none">Gestionar Solicitudes</a></li>
                                    <li><a href="index.php?vista=dueno_reportes" class="text-decoration-none">Reportes de Uso</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-secondary text-white">
                            <h2 class="h6 mb-0">Ubicación Física - Epicentro Shopping</h2>
                        </div>
                        <div class="card-body p-0">
                            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-60.6285%2C-32.9607%2C-60.6249%2C-32.9587&amp;layer=mapnik" 
                                    style="border: 0; width: 100%; height: 300px;" 
                                    title="Ubicación del Shopping en Rosario"></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>