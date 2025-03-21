<?php

session_start();


if (isset($_SESSION['mensaje_error'])) {
    echo "<div class='alert alert-danger text-center'>" . $_SESSION['mensaje_error'] . "</div>";
    unset($_SESSION['mensaje_error']); 
}



include './private/functions_novedades.php';

$novedades = get_all_novedades();
$novedades = array_slice($novedades, 0, 5);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css"> 
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    
    <title>Epicentro Shopping - Inicio</title>

</head>
<body>

    <div class="wrapper">
    <?php include './includes/header.php'; ?>
        <main class="container">
            <section class="carrusel my-4">
                <h2 class="text-center my-4" >Novedades Recientes</h2>
                
                <div class="row justify-content-center">
                    <div class="col-10 col-md-8">
                    <button class="prev" onclick="prevSlide()">&#10094;</button>
                        <div class="carrusel-container position-relative">
                            <?php foreach ($novedades as $index => $novedad): ?>
                                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                                    style="background-image: url('./private/visualizar_imagen.php?novedad_id=<?php echo $novedad['id']; ?>');">
                                    <a href="./novedades.php" class="slide-link">
                                        <div class="caption text-center p-2">
                                            <h3><?php echo htmlspecialchars($novedad['tituloNovedad']); ?></h3>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        
                        <button class="next" onclick="nextSlide()">&#10095;</button>
                    </div>
                </div>
            </section>
        </main>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script src="./carrusel.js"></script>
</body>
</html>


