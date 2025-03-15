<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_usuarios.php';
$categorias = get_categorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Agregar Novedad</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <main>
            <section class="admin-section">
                <h1 class="mb-4">Agregar Novedad</h1>

                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger'>".$_SESSION['error']."</p>";
                    unset($_SESSION['error']);  
                }
                ?>

                <form action="../private/alta_novedad.php" method="post">
                    <div class="form-group">
                        <label for="titulo_novedad">Título de la novedad:</label>
                        <input type="text" id="titulo_novedad" name="titulo_novedad" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="texto_novedad">Texto de la novedad:</label>
                        <textarea id="texto_novedad" name="texto_novedad" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha_desde">Fecha desde:</label>
                        <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_hasta">Fecha hasta:</label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <?php
                                foreach ($categorias as $categoria) {
                                    echo "<option value='{$categoria}'>{$categoria}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </section>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
