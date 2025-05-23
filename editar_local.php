<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_locales.php';
include './private/functions_usuarios.php';
include './private/rubros.php';


if (isset($_GET['id'])) {
    $local = get_local($_GET['id']);

    $dueños = get_all_dueños();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Modificación de Locales</title>
</head>
<body>
    <div class="wrapper">


        <?php include './includes/header.php'; ?>
        <div class="container mt-5">
            <main>
                <section class="admin-section">
                    <h1 class="admin-title">Modificar Local</h1>
                    <form id="localesForm" method="POST" action="./private/update_local.php" enctype="multipart/form-data" class="form-style">
                        <!-- Código del Local -->
                        <div class="form-group">
                            <label for="codigo_local">Código Local</label>
                            <input type="text" id="codigo_local" name="id_local" value="<?php echo $local['id']; ?>" readonly class="form-control">
                        </div>


                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="nombre_local">Nombre</label>
                            <input type="text" id="nombre_local" name="nombre_local" value="<?php echo $local['nombre']; ?>" required class="form-control">
                        </div>

                        <!-- Ubicación -->
                        <div class="form-group">
                            <label for="ubicacion_local">Ubicación</label>
                            <input type="text" id="ubicacion_local" name="ubicacion_local" value="<?php echo $local['ubicacion']; ?>" required class="form-control">
                        </div>

                        <!-- Rubro -->
                        <div class="form-group">
                            <label for="rubro_local">Rubro</label>
                            <select id="rubro_local" name="rubro_local" required class="form-control">
                            <option value="" disabled <?php echo empty($local['rubro']) ? 'selected' : ''; ?>>Seleccione un rubro</option>
                                <?php foreach ($rubros as $label => $value): ?>
                                    <option value="<?php echo $value; ?>" <?php echo ($value == $local['rubro']) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Email Dueño -->
                        <div class="form-group">
                            <label for="email_dueño">Email Dueño</label>
                            <select id="email_dueño" name="id_dueño" required class="form-control">
                            <option value="" disabled <?php echo empty($local['idUsuario']) ? 'selected' : ''; ?>>Seleccione un dueño</option>
                                <?php foreach ($dueños as $dueño): ?>
                                    <option value="<?php echo $dueño['id']; ?>" <?php echo ($dueño['id'] == $local['idUsuario']) ? 'selected' : ''; ?>>
                                        <?php echo $dueño['email']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Imagen del Local -->
                        <div class="form-group">
                            <label for="imagen_local">Imagen</label>
                            <?php if (!empty($local['imagen'])): ?>
                                <img src="./private/visualizar_imagen.php?local_id=<?php echo $local['id']; ?>" alt="Imagen del local" style="max-width: 80px;
                                height: auto;">
                            <?php else: ?>
                                <p>No hay imagen disponible</p>
                            <?php endif; ?>
                            <input type="file" id="imagen_local" name="imagen_local" class="form-control mt-2" accept=".png, .jpeg, .jpg">
                        </div>

                        <!-- Botón Aplicar Cambios -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success">Aplicar cambios</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
