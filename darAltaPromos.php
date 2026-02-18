<?php
session_start();
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
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/darAltaPromos.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Alta de Promociones</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <main class="container my-4">
        <h2 class="text-center my-4">Agregar Nueva Promoción</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']); ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <form action="./private/crud/promociones.php" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="local_id">Nombre del Local:</label>
                <select id="local_id" name="local_id" class="form-control" required>
                    <option value="">Seleccione un local</option>
                    <?php foreach ($locales as $local): ?>
                        <option value="<?= htmlspecialchars($local['id']); ?>"><?= htmlspecialchars($local['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="textoPromo">Texto de la Promoción:</label>
                <textarea id="textoPromo" name="textoPromo" class="form-control" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Días de la Semana:</label>
                <div class="form-check">
                    <label><input type="checkbox" name="diasSemana[]" value="Lunes"> Lunes</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Martes"> Martes</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Miércoles"> Miércoles</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Jueves"> Jueves</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Viernes"> Viernes</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Sábado"> Sábado</label>
                    <label><input type="checkbox" name="diasSemana[]" value="Domingo"> Domingo</label>
                </div>
            </div>

            <div class="form-group">
                <label for="categoriaCliente">Categoría de Cliente:</label>
                <select id="categoriaCliente" name="categoriaCliente" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    <option value="Inicial">Inicial</option>
                    <option value="Medium">Medium</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>
            
            <input type="submit" value="Agregar Promoción" class="btn btn-primary">
        </form>
    </main>
    
    <?php include './includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>