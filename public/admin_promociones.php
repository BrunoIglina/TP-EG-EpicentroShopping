<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Administración de Promociones</h1>
            <button onclick="location.href='nueva_promocion.php'">Agregar Promoción</button>
            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Código promoción</th>
                        <th>Promoción</th>
                        <th>Fecha desde promoción</th>
                        <th>Fecha hasta promoción</th>
                        <th>Categoría cliente</th>
                        <th>Días de la semana</th>
                        <th>Estado de la promoción</th>
                        <th>Código local</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>301</td>
                        <td>Texto promoción 1</td>
                        <td>01/01/2024</td>
                        <td>31/01/2024</td>
                        <td>Premium</td>
                        <td>Martes</td>
                        <td>Pendiente</td>
                        <td>102</td>
                    </tr>
                </tbody>
            </table>
            <div class="actions">
                <button onclick="editSelected()">Editar Seleccionados</button>
                <button onclick="deleteSelected()">Eliminar Seleccionados</button>
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>