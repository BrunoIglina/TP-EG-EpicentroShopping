<?php

session_start();

include './private/functions_promociones.php';

$promociones = get_all_promociones_activas();
$promociones = array_slice($promociones, 0, 5);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/wrapper.css"> 
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    
    <title>Epicentro Shopping - Inicio</title>

</head>
<body>

    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main class="container-fluid">
            <?php
            if (isset($_SESSION['mensaje_error'])) {
                echo "<div class='alert alert-danger text-center'>" . $_SESSION['mensaje_error'] . "</div>";
                unset($_SESSION['mensaje_error']); 
            }
            ?>

        <h2 class="text-center my-4" >PROMOCIONES ACTUALES</h2>

        <div class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
            <?php foreach ($promociones as $index => $promocion): 
                if ($index == 0): ?>
                <div class="carousel-item active">
                <?php else: ?>
                <div class="carousel-item">
                <?php endif; ?>
                    <a href="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/locales.php" class="slide-link">                
                        <img src='./private/visualizar_imagen.php?local_id=<?php echo $promocion['local_id']; ?>' class="image-carousel d-block w-100 " alt="Promociones">
                        <div class="carousel-caption d-none d-md-block">
                            <h3 style = "color: #000"><?php echo htmlspecialchars($promocion['textoPromo']); ?></h3>
                        </div>
                    </a>        
                </div>
            <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        </main>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>


