<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_usuarios.php';
$categorias = get_categorias();
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
    <title>Epicentro Shopping - Agregar Novedad</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <div class="container mt-5">
        <main>
            <section class="admin-section">
                

                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger'>".$_SESSION['error']."</p>";
                    unset($_SESSION['error']);  
                }
                ?>

                <form action="./private/alta_novedad.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo_novedad"><strong>Titulo de la Novedad</strong></label>
                        <input type="text" id="titulo_novedad" name="titulo_novedad" class="form-control" placeholder="Ingrese título de la novedad" required>
                    </div>

                    <div class="form-group">
                        <label for="texto_novedad"><b>Texto de la Novedad</b></label>
                        <textarea id="texto_novedad" name="texto_novedad" class="form-control" rows="4" placeholder="Ingrese descripcion de la novedad" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha_desde"><b>Fecha desde</b></label>
                        <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_hasta"><b>Fecha hasta</b></label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="categoria"><b>Categoría</b></label>
                        <select id="categoria" name="categoria" class="form-control" required>
                        <option value="" disabled selected>Seleccione una categoria</option>
                            <?php
                                foreach ($categorias as $categoria) {
                                    echo "<option value='{$categoria}'>{$categoria}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="imagen_novedad"><strong>Imagen de la novedad</strong></label>
                        <input type="file" id="imagen_novedad" name="imagen_novedad" class="form-control" accept=".png"  required>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </section>
        </main>
    </div>
    <?php include './includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
