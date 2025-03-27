<?php
require './private/mail_contacto.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/contacto.css">
    <title>Contacto - Epicentro Shopping</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
            <?php
            if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Tu mensaje ha sido enviado con éxito.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Hubo un problema al enviar tu mensaje. Inténtalo de nuevo.</div>
        <?php endif; ?>

    <main class="container compact-container my-4">
        <h2 class="text-center">Contacto</h2>
        
        <div class="accordion" id="faqAccordion">
            <div class="card">
                <div class="card-header" id="faq1">
                    <h2 class="mb-0">
                        <button class="btn btn-link faq-btn" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                            ¿Cómo puedo registrarme en Epicentro Shopping como cliente?
                        </button>
                    </h2>
                </div>
                <div id="collapse1" class="collapse" aria-labelledby="faq1" data-parent="#faqAccordion">
                    <div class="card-body">
                        Para registrarte como cliente, debes dirigirte a iniciar sesion, seleccionar Registro y completar el formulario con tus datos personales, te llegara un correo de confirmacion para validar tu cuenta.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="faq2">
                    <h2 class="mb-0">
                        <button class="btn btn-link faq-btn" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            ¿Cómo hago uso de las Promociones?
                        </button>
                    </h2>
                </div>
                <div id="collapse2" class="collapse" aria-labelledby="faq2" data-parent="#faqAccordion">
                    <div class="card-body">
                        Para utilizar Promociones debes seleccionar el local y Promocion que desees utilizar, en la seccion Solicitudes, veráz el estado de la Promocion que solicitaste.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="faq3">
                    <h2 class="mb-0">
                        <button class="btn btn-link faq-btn" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            ¿Cómo contacto con soporte?
                        </button>
                    </h2>
                </div>
                <div id="collapse3" class="collapse" aria-labelledby="faq3" data-parent="#faqAccordion">
                    <div class="card-body">
                        Si necesitas ayuda, puedes escribirnos mediante el formulario de contacto o enviarnos un correo a soporte@epicentroshopping.com.
                    </div>
                </div>
            </div>
        </div>
        
        <form action="./private/mail_contacto.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Enviar</button>
        </form>
    </main>
    
    <?php include './includes/footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.faq-btn').click(function() {
                var target = $(this).attr('data-target');
                if ($(target).hasClass('show')) {
                    $(target).collapse('hide');
                } else {
                    $('.collapse').collapse('hide');
                    $(target).collapse('show');
                }
            });
        });
    </script>
</body>
</html>