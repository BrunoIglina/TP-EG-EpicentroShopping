<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Modificar Local: Adidas | Epicentro Shopping</title>
    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/forms.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow" style="background-color: #202833;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php?vista=landing" aria-label="Ir a la página principal">
                    <img src="public/assets/logo2.png" alt="Logotipo de Epicentro Shopping - Volver al Inicio" width="120">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Abrir menú de navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" role="dialog" style="background-color: #202833;">
                    <div class="offcanvas-header">
                        <h2 class="offcanvas-title h5 text-white" id="offcanvasNavbarLabel">Menú de Navegación</h2>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar menú"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1">
                            <li class="nav-item"><a class="nav-link text-white" href="index.php?vista=landing">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="index.php?vista=locales">Locales</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="index.php?vista=mapadesitio">Mapa</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="index.php?vista=novedades">Novedades</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="dropAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">Gestionar Shopping</a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropAdmin">
                                    <li><a class="dropdown-item" href="index.php?vista=admin_locales">Gestionar Locales</a></li>
                                    <li><a class="dropdown-item" href="index.php?vista=admin_novedades">Gestionar Novedades</a></li>
                                    <li><a class="dropdown-item" href="index.php?vista=admin_promociones">Gestionar Promociones</a></li>
                                    <li><a class="dropdown-item" href="index.php?vista=admin_aprobar_clientes">Gestionar Clientes</a></li>
                                    <li><a class="dropdown-item" href="index.php?vista=admin_aprobar_duenos">Gestionar Dueños</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link text-white fw-bold" href="index.php?vista=cliente_perfil">Mi Perfil</a></li>
                            <li class="nav-item"><a class="nav-link text-danger fw-bold" href="public/logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content" class="form py-4">
        <div class="container">
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <a href="/index.php?go_back=1" class="btn btn-outline-secondary btn-back-custom" title="Volver a la página anterior">
                        &larr; <span class="d-none d-md-inline">Volver</span>
                    </a>
                </div>
                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">Modificar Local</h1>
                </div>
                <div class="col-2 col-md-1"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="form-card shadow-lg p-4 rounded-4 bg-white">
                        <form method="POST" action="index.php" enctype="multipart/form-data">
                            <input type="hidden" name="modulo" value="admin">
                            <input type="hidden" name="accion" value="editar_local">
                            <input type="hidden" name="id_local" value="16">
                            <input type="hidden" name="nombre_antiguo_local" value="Adidas">

                            <div class="mb-3">
                                <label class="form-label">Código Local</label>
                                <input type="text" class="form-control" value="16" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_local" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre_local" name="nombre_local" value="Adidas" required>
                            </div>
                            <div class="mb-3">
                                <label for="ubicacion_local" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion_local" name="ubicacion_local" value="Shopping Sur" required>
                            </div>
                            <div class="mb-3">
                                <label for="rubro_local" class="form-label">Rubro</label>
                                <select class="form-select" id="rubro_local" name="rubro_local" required>
                                    <option value="" disabled>Seleccione un rubro</option>
                                    <option value="Ropa">Ropa</option>
                                    <option value="Electrónica">Electrónica</option>
                                    <option value="Joyería">Joyería</option>
                                    <option value="Calzado">Calzado</option>
                                    <option value="Librería">Librería</option>
                                    <option value="Alimentos">Alimentos</option>
                                    <option value="Bebidas">Bebidas</option>
                                    <option value="Farmacia">Farmacia</option>
                                    <option value="Deportes">Deportes</option>
                                    <option value="Muebles">Muebles</option>
                                    <option value="Hogar">Hogar</option>
                                    <option value="Automóviles">Automóviles</option>
                                    <option value="Belleza">Belleza</option>
                                    <option value="Viajes">Viajes</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_dueno_select" class="form-label fw-bold">Email Dueño</label>
                                <select class="form-select" id="id_dueno_select" name="id_dueño" required>
                                    <option value="" disabled>Seleccione un dueño</option>
                                    <option value="6">brunoniglina@gmail.com</option>
                                    <option value="16">brunoiglinadev@gmail.com</option>
                                    <option value="17">dueño1@zara.com</option>
                                    <option value="18">dueño2@nike.com</option>
                                    <option value="19" selected>dueño3@adidas.com</option>
                                    <option value="20">dueño4@sport78.com</option>
                                    <option value="21">dueño5@mcdonalds.com</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <p class="form-label fw-bold">Imagen Actual</p>
                                <div class="text-center p-2 border rounded bg-light">
                                    <img src="index.php?vista=imagen&local_id=16" alt="Vista previa actual del local Adidas" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="imagen_local" class="form-label fw-bold">Nueva Imagen (Opcional)</label>
                                <input type="file" class="form-control" id="imagen_local" name="imagen_local" accept="image/png, image/jpeg, image/jpg">
                                <div class="form-text">Si no selecciona una, se mantendrá la imagen anterior.</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">APLICAR CAMBIOS</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='index.php?vista=admin_locales'">Cancelar y Volver</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="text-center py-4 bg-dark text-white-50 mt-auto">
        <p class="mb-1">Epicentro Shopping - Todos los derechos reservados &copy; 2026</p>
        <p class="m-0">Contacto: <a href="mailto:admin@epicentroshopping.com" class="text-white text-decoration-underline">admin@epicentroshopping.com</a></p>
    </footer>
    <a href="index.php?vista=contacto" class="floating-contact-btn">
        <span class="floating-contact-btn-icon">✉️</span>
        <span>Contacto</span>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>