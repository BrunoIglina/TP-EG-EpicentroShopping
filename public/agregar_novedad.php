<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/novedades_functions.php';
$categorias = get_categorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Agregar Novedad</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Agregar novedad</h1>
            <form action="../private/alta_novedad.php" method="post">

                <label for="texto_novedad">Texto de la novedad:</label>
                <input type="textarea" id="texto_novedad" name="texto_novedad" required>

                <label for="fecha_desde">Fecha desde:</label>
                <input type="date" id="fecha_desde" name="fecha_desde" required>

                <label for="fecha_hasta">Fecha hasta:</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" required>

                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <?php
                        foreach ($categorias as $categoria) {
                            $selected = ($categoria == $novedad['categoria']) ? 'selected' : '';
                            echo "<option value='{$categoria}' $selected>{$categoria}</option>";
                        }
                    ?>
                </select>

                <button type="submit">Registrar</button>

            </form>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>