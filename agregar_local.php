<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

require_once './private/functions/functions_usuarios.php';
require_once './config/rubros.php';

$dueños = get_all_dueños();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/forms.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">

    <title>Epicentro Shopping - Agregar Local</title>
</head>
<body>
        <?php include './includes/header.php'; ?>
    <div class="wrapper">
                <?php include './includes/back_button.php'; ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="form-card">
                        <h2>Agregar Local</h2>
                        
                        <?php if (isset($_SESSION['mensaje_error1'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo htmlspecialchars($_SESSION['mensaje_error1']); unset($_SESSION['mensaje_error1']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="./private/crud/locales.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label for="nombre_local" class="form-label">Nombre del local</label>
                                <input type="text" class="form-control" id="nombre_local" name="nombre_local" 
                                       placeholder="Ingrese nombre del local" required>
                            </div>

                            <div class="mb-3">
                                <label for="ubicacion_local" class="form-label">Ubicación del local</label>
                                <input type="text" class="form-control" id="ubicacion_local" name="ubicacion_local" 
                                       placeholder="Ingrese ubicación del local" required>
                            </div>

                            <div class="mb-3">
                                <label for="rubro_local" class="form-label">Rubro del local</label>
                                <select class="form-select" id="rubro_local" name="rubro_local" required>
                                    <option value="" disabled selected>Seleccione un rubro</option>
                                    <?php foreach ($rubros as $label => $value): ?>
                                        <option value="<?php echo htmlspecialchars($value); ?>">
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email_dueño" class="form-label">Email dueño del local</label>
                                <select class="form-select" id="email_dueño" name="email_dueño" required>
                                    <option value="" disabled selected>Seleccione un Dueño</option>
                                    <?php foreach ($dueños as $dueño): ?>
                                        <option value="<?php echo htmlspecialchars($dueño['email']); ?>">
                                            <?php echo htmlspecialchars($dueño['email']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="imagen_local" class="form-label">Imagen del local</label>
                                <input type="file" class="form-control" id="imagen_local" name="imagen_local" 
                                       accept=".png, .jpeg, .jpg" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gradient">Registrar</button>
                                <button type="button" class="btn btn-secondary" 
                                        onclick="window.location.href='admin_locales.php'">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include './includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>