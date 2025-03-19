<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_novedades.php';
include './private/functions_usuarios.php';

if (isset($_GET['id'])) {
    $novedad = get_novedad($_GET['id']);
    $categorias = get_categorias();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <title>Epicentro Shopping - Modificación de Novedades</title>
    <?php include_once '../private/functions_novedades.php'; ?>
</head>
<body>
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
    <h2 class="text-center my-4">Modificar novedades</h2>
            <main>

            <section class="admin-section">
                <h1>Modificar Novedad</h1>
                <form id="localesNovedadesForm" method="POST" action="../private/update_novedad.php" enctype="multipart/form-data" class="form-style">
                    <!-- Código Novedad (Solo lectura) -->
                    <div class="form-group">
                        <label for="codigo_novedad">Código Novedad</label>
                        <input type="text" id="codigo_novedad" name="id_novedad" value="<?php echo $novedad['id']; ?>" readonly class="form-control">
                    </div>


                    <!-- Título -->
                    <div class="form-group">
                        <label for="titulo_novedad">Título</label>
                        <input type="text" id="titulo_novedad" name="titulo_novedad" value="<?php echo $novedad['tituloNovedad']; ?>" required class="form-control">
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="texto_novedad">Descripción</label>
                        <textarea id="texto_novedad" name="texto_novedad" rows="4" required class="form-control"><?php echo $novedad['textoNovedad']; ?></textarea>
                    </div>

                    <!-- Fecha Desde -->
                    <div class="form-group">
                        <label for="fecha_desde">Fecha Desde</label>
                        <input type="date" id="fecha_desde" name="fecha_desde" value="<?php echo $novedad['fecha_desde']; ?>" required class="form-control">
                    </div>

                    <!-- Fecha Hasta -->
                    <div class="form-group">
                        <label for="fecha_hasta">Fecha Hasta</label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php echo $novedad['fecha_hasta']; ?>" required class="form-control">
                    </div>

                    <!-- Categoría -->
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" required class="form-control">
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria; ?>" <?php echo ($categoria == $novedad['categoria']) ? 'selected' : ''; ?>>
                                    <?php echo $categoria; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Imagen -->
                    <div class="form-group">
                        <label for="imagen_novedad">Imagen</label>
                        <?php if (!empty($novedad['imagen'])): ?>
                            <img src="../private/visualizar_imagen.php?novedad_id=<?php echo $novedad['id']; ?>" alt="Imagen de la novedad" style="max-width: 80px;
                            height: auto;">
                        <?php else: ?>
                            <p>No hay imagen disponible</p>
                        <?php endif; ?>
                        <input type="file" id="imagen_novedad" name="imagen_novedad" class="form-control mt-2" accept=".png, .jpeg, .jpg">
                    </div>

                    <!-- Botón Aplicar Cambios -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Aplicar cambios</button>
                    </div>
                </form>
            </section>

        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
