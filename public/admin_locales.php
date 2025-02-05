<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Locales</title>
    <?php include '../private/locales_functions.php'; ?>
    <?php include '../private/usuarios_functions.php'; ?>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Administración de Locales</h1>
            <button onclick="location.href='agregar_local.php'">Agregar Local</button>
            <form id="localesForm" method="POST" action="../private/procesar_local.php">
                <button type="submit" name="action" class="select-toggle" value="toggle">
                    <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'Deseleccionar Todo' : 'Seleccionar Todo'; ?>
                </button>
                <table>
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Código del local</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Rubro</th>
                            <th>Email del dueño</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $locales = get_all_locales();
                            while ($local = mysqli_fetch_assoc($locales)) { ?>
                            <tr>
                                <td><input type="checkbox" name="locales[]" value="<?php echo $local['id']; ?>" <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>></td>
                                <td><?php echo $local['id']?></td>
                                <td><?php echo $local['nombre']?></td>
                                <td><?php echo $local['ubicacion']?></td>
                                <td><?php echo $local['rubro']?></td>
                                <?php 
                                        $result_dueño = get_dueño($local['idUsuario']);
                                        $dueño = $result_dueño -> fetch_assoc();
                                    ?>
                                <td><?php echo $dueño['email']?></td>    
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button type="submit" name="action" class="green" value="edit">Modificar local</button>
                <button type="submit" name="action" class="red" value="delete">Eliminar local</button>
                <input type="hidden" name="select_all" value="<?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? '0' : '1'; ?>">
            </form>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
