<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Locales</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Administración de Locales</h1>
            <button onclick="location.href='nuevo_local.php'">Agregar Local</button>
            
            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Código del local</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Rubro</th>
                        <th>Código del dueño</th>
                        <th>Dueño</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>101</td>
                        <td>Local 1</td>
                        <td>Piso 1</td>
                        <td>Tienda de ropa</td>
                        <td>001</td>
                        <td>Jorge</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>102</td>
                        <td>Local 2</td>
                        <td>Piso 1</td>
                        <td>Ferretería</td>
                        <td>002</td>
                        <td>María</td>
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