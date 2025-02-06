<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Inicio</title>
</head>
<body>

    <?php include '../includes/header.php'; ?>
    <main>
        <section class="carrusel">
            <h1>Destacados del Mes</h1>
            <div class="carrusel-container">
                <div class="slide active">
                    <img src="../assets/tecnologia.png" alt="Descuento en Tecnología">
                    <div class="caption">Hasta un 30% de descuento en tecnología</div>
                </div>
                <div class="slide">
                    <img src="../assets/tiendaropa.png" alt="Nueva Colección de Moda">
                    <div class="caption">Descubre la nueva colección de moda</div>
                </div>
                <div class="slide">
                    <img src="../assets/jugueteria.png" alt="Apertura de Local de Juguetes">
                    <div class="caption">Gran apertura del local de juguetes</div>
                </div>
                
            </div>
            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </section>
    </main>
   
    <?php include '../includes/footer.php'; ?>
    <script src="../carrusel.js"></script>
</body>
</html>