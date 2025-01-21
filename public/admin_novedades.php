<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Novedades</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Administración de Novedades</h1>
            <button onclick="location.href='nueva_novedad.php'">Agregar Novedad</button>
            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Código novedad</th>
                        <th>Novedad</th>
                        <th>Fecha desde novedad</th>
                        <th>Fecha hasta novedad</th>
                        <th>Tipo de usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>201</td>
                        <td>Texto novedad 1</td>
                        <td>01/01/2024</td>
                        <td>31/01/2024</td>
                        <td>Cliente</td>
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