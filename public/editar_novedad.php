<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_novedades.php';
include '../private/functions_usuarios.php';

if (isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']); // Convertir la cadena de IDs en un array
    foreach ($ids as $id_novedad){
        $novedades[] = get_novedad($id_novedad);
    }
    $categorias = get_categorias();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <title>Epicentro Shopping - Modificación de Novedades</title>
    <?php include_once '../private/functions_novedades.php'; ?>
</head>
<body>
    <div class="wrapper">
    <?php include '../includes/header.php'; ?>
            <main>

                <section class="admin-section">
                    <h2>Modificar novedades</h2>
                    <table>

                        <thead>
                            <tr>
                                <th>Código novedad</th>
                                <th>Título</th>
                                <th>Descripcion</th>
                                <th>Fecha desde</th>
                                <th>Fecha hasta</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            <form id="novedadesForm" method="POST" action="../private/update_novedad.php">
                                    <?php
                                        foreach ($novedades as $novedad){
                                    ?>
                                            <tr>

                                                <td><?php echo $novedad['id']?><input type="hidden" name="id_novedad[]" value="<?php echo $novedad['id']?>"></td>

                                                <td><input type="textarea" name="titulo_novedad[]" value="<?php echo $novedad['tituloNovedad']?>" required></td>


                                                <td><input type="textarea" name="texto_novedad[]" value="<?php echo $novedad['textoNovedad']?>" required></td>

                                                <td><input type="date" name="fecha_desde[]" value="<?php echo $novedad['fecha_desde']?>" required></td>

                                                <td><input type="date" name="fecha_hasta[]" value="<?php echo $novedad['fecha_hasta']?>" required></td>

                                                <td><select id="categoria" name="categoria[]" required>
                                                    <?php
                                                    foreach ($categorias as $categoria) {
                                                        $selected = ($categoria == $novedad['categoria']) ? 'selected' : '';
                                                        echo "<option value='{$categoria}' $selected>{$categoria}</option>";
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
