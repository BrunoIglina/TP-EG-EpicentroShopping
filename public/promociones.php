<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Promociones</h1>
        <section class="promo-filters">
            <label for="category">Filtrar por categoría:</label>
            <select id="category" name="category">
                <option value="todos">Todos</option>
                <option value="ropa">Ropa</option>
                <option value="tecnología">Tecnología</option>
                
            </select>
        </section>

        <section class="promotions-list">
            <div class="promotion-card">
                <h2>Promo 1</h2>
                <p>Descuento del 20% en ropa seleccionada.</p>
            </div>
            
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>