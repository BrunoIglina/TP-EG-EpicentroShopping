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
    <title>Epicentro Shopping - Administración de Locales</title>
    <?php include '../private/locales_functions.php'?>
    <?php include '../private/usuarios_functions.php'?>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>

        <section class="admin-section">
            <h1>Modifica locales</h1>
            <table>

                <thead>
                    <tr>
                        <th>Código local</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Rubro</th>
                        <th>Email del dueño</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                        if (isset($_GET['ids'])) {
                            $ids = explode(',', $_GET['ids']); // Convertir la cadena de IDs en un array
                            foreach ($ids as $id_local) {
                                $locales[] = mysqli_fetch_assoc($result_local = get_local($id_local));
                            }
                        }
                    ?>

                <form id="localesForm" method="POST" action="../private/update_local.php">
                        <?php
                            foreach ($locales as $local){?>
                                <tr>

                                    <td><?php echo $local['id']?><input type="hidden" name="id_local[]" value="<?php echo $local['id']?>"</td>

                                    <td><input type="text" name="nombre_local[]" value="<?php echo $local['nombre']?>" required></td>

                                    <td><input type="text" name="ubicacion_local[]" value="<?php echo $local['ubicacion']?>" required></td>

                                    <td><input type="text" name="rubro_local[]" value="<?php echo $local['rubro']?>" required></td>

                                    <?php 
                                        $result_dueño = get_dueño($local['idUsuario']);
                                        $dueño = $result_dueño -> fetch_assoc();
                                    ?>

                                    <td><input type="text" name="email[]" value="<?php echo $dueño['email'] ?>" required></td>
                                    
                                </tr>
                            <?php 
                            }?>
                    <button type="submit">Aplicar cambios</button>
                </form>
            </tbody>
        </table>
    </section>
</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
