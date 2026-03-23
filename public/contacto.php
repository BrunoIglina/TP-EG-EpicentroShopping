<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">
    <link rel="stylesheet" href="public/css/contacto.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="public/css/buttons.css">

    <title>Contacto - Epicentro Shopping</title>
</head>

<body class="wrapper">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="container compact-container py-4">
        <div class="row align-items-center mb-5 mt-3">
            <div class="col-2 col-md-1 text-start">
                <?php include __DIR__ . '/../includes/back_button.php'; ?>

            </div>
            <div class="col-8 col-md-10">
                <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
                    Contacto
                </h2>
            </div>
            <div class="col-2 col-md-1"></div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Tu mensaje ha sido enviado con éxito.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                Hubo un problema al enviar tu mensaje. Inténtalo de nuevo.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="accordion mb-5 shadow-sm" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                        ¿Cómo puedo registrarme en Epicentro Shopping como cliente?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Para registrarte como cliente, debes dirigirte a <strong>Iniciar Sesión</strong>, seleccionar <strong>Registro</strong> y completar el formulario. Te llegará un correo de confirmación.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                        ¿Cómo hago uso de las Promociones?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Seleccioná el local y la promoción que desees. En la sección <strong>Solicitudes</strong> podrás ver el estado de tu pedido.
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h4 class="mb-4 text-center fw-bold">Envíanos un mensaje</h4>
                    <form action="private/logic/helpers/procesar_contacto.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresá tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label fw-bold">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="¿En qué podemos ayudarte?" required></textarea>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-gradient py-2">ENVIAR MENSAJE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>