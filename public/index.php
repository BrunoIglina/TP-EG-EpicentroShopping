<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css"> 
    <title>Epicentro Shopping - Inicio</title>
</head>
<body>

    <?php include '../includes/header.php'; ?>
    <main class="container">
        <section class="carrusel my-4">
            <h1 class="text-center">Destacados del Mes</h1>
            <div class="carrusel-container position-relative">
                <div class="slide active">
                    <img src="../assets/tecnologia.png" class="d-block w-100" alt="Descuento en Tecnología">
                    <div class="caption">Hasta un 30% de descuento en tecnología</div>
                </div>
                <div class="slide">
                    <img src="../assets/tiendaropa.png" class="d-block w-100" alt="Nueva Colección de Moda">
                    <div class="caption">Descubre la nueva colección de moda</div>
                </div>
                <div class="slide">
                    <img src="../assets/jugueteria.png" class="d-block w-100" alt="Apertura de Local de Juguetes">
                    <div class="caption">Gran apertura del local de juguetes</div>
                </div>
            </div>
            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </section>
    </main>
   
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../carrusel.js"></script>
</body>
</html>