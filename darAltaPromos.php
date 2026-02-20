<?php
require_once './includes/navigation_history.php';
require_once './config/database.php';
$conn = getDB();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, nombre FROM locales WHERE idUsuario = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$locales = [];
while ($row = $result->fetch_assoc()) {
    $locales[] = $row;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/forms.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Agregar Promoción</title>
</head>
<body>
        <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>
    
    <div class="form-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="form-card">
                        <h2>Agregar Nueva Promoción</h2>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="./private/crud/promociones.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label for="local_id" class="form-label">Nombre del Local</label>
                                <select id="local_id" name="local_id" class="form-select" required>
                                    <option value="">Seleccione un local</option>
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?php echo htmlspecialchars($local['id']); ?>">
                                            <?php echo htmlspecialchars($local['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="textoPromo" class="form-label">Texto de la Promoción</label>
                                <textarea id="textoPromo" name="textoPromo" class="form-control" 
                                          rows="4" required maxlength="200"
                                          placeholder="Ej: 2x1 en todos los productos"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" 
                                           class="form-control" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" 
                                           class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Días de la Semana</label>
                                <div class="row">
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Lunes" id="lunes">
                                            <label class="form-check-label" for="lunes">Lunes</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Martes" id="martes">
                                            <label class="form-check-label" for="martes">Martes</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Miércoles" id="miercoles">
                                            <label class="form-check-label" for="miercoles">Miércoles</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Jueves" id="jueves">
                                            <label class="form-check-label" for="jueves">Jueves</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Viernes" id="viernes">
                                            <label class="form-check-label" for="viernes">Viernes</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Sábado" id="sabado">
                                            <label class="form-check-label" for="sabado">Sábado</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="diasSemana[]" value="Domingo" id="domingo">
                                            <label class="form-check-label" for="domingo">Domingo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="categoriaCliente" class="form-label">Categoría de Cliente</label>
                                <select id="categoriaCliente" name="categoriaCliente" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gradient">Agregar Promoción</button>
                                <button type="button" class="btn btn-secondary" 
                                        onclick="window.location.href='misPromos.php'">Cancelar</button>
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