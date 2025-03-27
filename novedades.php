<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje_error'] = "Iniciar sesión para observar las novedades";
    header("Location: index.php");
    exit();
}

include './private/functions_novedades.php';

$novedades = get_novedades_permitidas($_SESSION['user_id'],$_SESSION['user_tipo'],$_SESSION['user_categoria']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/tarjetas.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Novedades</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main>
            <section class="novedades container my-4">
                <h2 class="text-center my-4">Novedades</h2>
                <p>Explora las últimas novedades y noticias de Epicentro Shopping.</p>
                
                <div class="row d-flex flex-wrap">
                    <?php foreach ($novedades as $novedad) { ?>
                        <div class="col-12" style="padding:.5rem;">
                            <div class="card text-center h-100" style="padding:1rem">
                                <div class="card-image">
                                    <?php
                                    $novedad_id = $novedad['id'];
                                    echo '<img src="./private/visualizar_imagen.php?novedad_id=' . $novedad_id . '" alt="Imagen de la novedad">';
                                    ?>
                                </div>
                                <div class="card-text">
                                    <h2><?php echo $novedad['tituloNovedad']; ?></h2>
                                    <p><?php echo $novedad['fecha_desde']; ?></p>
                                    <p><?php echo $novedad['textoNovedad']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>