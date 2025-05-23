<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_usuarios.php'; 
include './private/rubros.php';

$dueños = get_all_dueños();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/wrapper.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Agregar Local</title>
</head>

<body>
    
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
    <?php
            if (isset($_SESSION['mensaje_error1'])) {
                echo "<div class='alert alert-danger text-center'>" . $_SESSION['mensaje_error1'] . "</div>";
                unset($_SESSION['mensaje_error1']); 
            }
    ?>
    <div class="container mt-5">
        <main>
            <section class="admin-section">
                
                <form action="./private/alta_local.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nombre_local">Nombre del local:</label>
                        <input type="text" id="nombre_local" name="nombre_local" class="form-control" placeholder="Ingrese nombre del local" required>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion_local">Ubicación del local:</label>
                        <input type="text" id="ubicacion_local" name="ubicacion_local" class="form-control" placeholder="Ingrese ubicación del local" required>
                    </div>

                    <div class="form-group">
                        <label for="rubro_local">Rubro del local:</label>
                        <select id="rubro_local" name="rubro_local" class="form-control" required>
                        <option value="" disabled selected>Seleccione un rubro</option>
                            <?php foreach ($rubros as $label => $value) { ?>
                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email_dueño">Email dueño del local:</label>
                        <select id="email_dueño" name="email_dueño" class="form-control" required>
                        <option value="" disabled selected>Seleccione un Dueño</option>
                            <?php
                                foreach ($dueños as $dueño) {
                                    echo "<option value='{$dueño['email']}'>{$dueño['email']}</option>";
                                }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="imagen_local">Imagen del local:</label>
                        <input type="file" id="imagen_local" name="imagen_local" class="form-control" accept=".png, .jpeg, .jpg" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
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
