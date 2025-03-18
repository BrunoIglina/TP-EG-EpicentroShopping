<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueño') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, nombre FROM locales WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$locales = [];
while ($row = $result->fetch_assoc()) {
    $locales[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/darAltaPromos.css">
    <link rel="stylesheet" href="../css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <title>Alta de Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container my-4">
        <h2 class="text-center my-4">Agregar Nueva Promoción</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success"><?= $_SESSION['mensaje']; ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <form action="../private/controladorPromos.php" method="POST">
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
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
