<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Promociones</title>
</head>
<body>
    <h1>Agregar Nueva Promoción</h1>
    <form action="controladorPromos.php" method="POST">
        <label for="local_nombre">Nombre del Local:</label>
        <input type="number" id="local_nombre" name="local_nombre" required><br><br>
        
        <label for="textoPromo">Texto de la Promoción:</label>
        <textarea id="textoPromo" name="textoPromo" required></textarea><br><br>
        
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required><br><br>
        
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required><br><br>
        
        <label for="diasSemana">Días de la Semana:</label>
        <input type="text" id="diasSemana" name="diasSemana"><br><br>
        
        <label for="categoriaCliente">Categoría de Cliente:</label>
        <input type="text" id="categoriaCliente" name="categoriaCliente"><br><br>
        
        <input type="submit" value="Agregar Promoción">
    </form>
</body>
</html>
