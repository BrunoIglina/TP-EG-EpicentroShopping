<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_locales.php';
include '../private/functions_usuarios.php';
include '../private/rubros.php';

if (isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']); // Convertir la cadena de IDs en un array
    foreach ($ids as $id_local){
        $locales[] = get_local($id_local);
    }
    $dueños = get_all_dueños();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Modificación de Locales</title>
    
</head>
<body>
    <div class="wrapper">
    <?php include '../includes/header.php'; ?>
            <main>

                <section class="admin-section">
                    <h1>Modificar locales</h1>
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
                            <form id="localesForm" method="POST" action="../private/update_local.php">
                                    <?php
                                        foreach ($locales as $local){?>

                                        <input type="hidden" name="nombre_antiguo_local[]" value="<?php echo $local['nombre']?>">

                                            <tr>

                                                <td><?php echo $local['id']?><input type="hidden" name="id_local[]" value="<?php echo $local['id']?>"></td>

                                                <td><input type="text" name="nombre_local[]" value="<?php echo $local['nombre']?>" required></td>

                                                <td><input type="text" name="ubicacion_local[]" value="<?php echo $local['ubicacion']?>" required></td>

                                                <?php $selected_rubro = $local['rubro'] ?>

                                                <td>
                                                <select name="rubro_local[]" required>
                                                    <?php foreach ($rubros as $label => $value) { ?>
                                                        <option value="<?php echo $value; ?>" <?php echo ($value == $selected_rubro) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>

                                                <td><select id="email_dueño" name="id_dueño[]" required>
                                                <?php
                                                    foreach ($dueños as $dueño) {
                                                        $selected = ($dueño['id'] == $local['idUsuario']) ? 'selected' : '';
                                                        echo "<option value='{$dueño['id']}' $selected>{$dueño['email']}</option>";
                                                    }
                                                ?>
                                                </select></td>
                                                
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
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
