<?php
session_start();

include './private/functions_locales.php';
include './private/rubros.php';

$locales = get_locales_solicitados();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/wrapper.css"> 
    <link rel="stylesheet" href="./css/tarjetas.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Inicio</title>
</head>


<body>
<div class="ratio ratio-16x9 position-relative">
    <video src="./assets/file.mp4" class="w-100" autoplay muted loop playsinline></video>
        <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100 text-white text-center" style="background: rgba(0, 0, 0, 0.5);">
<h1>BIENVENIDO A SHOPPING EPICENTRO</h1>
</div>
</div>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>

        <main>


            <div class="container-fluid">

                <?php
                if (isset($_SESSION['mensaje_error'])) {
                    echo "<div class='alert alert-danger text-center'>" . $_SESSION['mensaje_error'] . "</div>";
                    unset($_SESSION['mensaje_error']); 
                }
                ?>

                <h2>NUESTROS LOCALES MAS SOLICITADOS</h2>

                <div class="row" style="background-color:rgba(32, 40, 51, 0.06); padding: 1rem; border-radius: 20px;">
                    <?php foreach ($locales as $local) { ?>
                        <div class="col-md-3 col-sm-12" style="padding: .5rem;">
                            <a href="promociones.php?local_id=<?php echo $local['id']; ?>&local_nombre=<?php echo urlencode($local['nombre']); ?>&local_rubro=<?php echo urlencode($local['rubro']); ?>" class="card-link">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <div class="card-image">
                                            <?php echo '<img src="./private/visualizar_imagen.php?local_id=' . $local['id'] . '" alt="Imagen de el local">'; ?>
                                        </div>
                                        <h4 class="card-title"><?php echo $local['nombre']; ?></h4>
                                        <p class="card-text">
                                            <?php echo $local['rubro']; ?><br>
                                            <?php echo $local['ubicacion']; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>

            </div>


        </main>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
