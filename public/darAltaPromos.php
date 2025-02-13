<?php
session_start();
include '../env/shopping_db.php';

// Verificar si el usuario está autenticado y es un dueño
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueño') {
    header("Location: index.php");
    exit();
}

// Obtener el ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Consultamos los locales del dueño
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
    <!--    <link rel="stylesheet" href="../css/styles.css"> -->
    <link rel="stylesheet" href="../css/darAltaPromos.css">
    <title>Alta de Promociones</title>
    <script src="../scripts/diasSem.js"></script> 
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container my-4">
        <h1>Agregar Nueva Promoción</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="../private/controladorPromos.php" method="POST" onsubmit="return validarFechas(event)">
            <div class="form-group">
                <label for="local_id">Nombre del Local:</label>
                <select id="local_id" name="local_id" class="form-control" required>
                    <option value="">Seleccione un local</option>
                    <?php foreach ($locales as $local) { ?>
                        <option value="<?= $local['id']; ?>"><?= $local['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="textoPromo">Texto de la Promoción:</label>
                <textarea id="textoPromo" name="textoPromo" class="form-control" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                <div id="fechaInicioError" class="error"></div>
            </div>
            
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                <div id="fechaFinError" class="error"></div>
            </div>
            
            <div class="form-group">
                <label>Días de la Semana:</label>
                <div class="days-of-week">
                    <label for="lunes"><input type="checkbox" id="lunes" name="diasSemana" value="Lunes"> Lunes</label>
                    <label for="martes"><input type="checkbox" id="martes" name="diasSemana" value="Martes"> Martes</label>
                    <label for="miércoles"><input type="checkbox" id="miércoles" name="diasSemana" value="Miércoles"> Miércoles</label>
                    <label for="jueves"><input type="checkbox" id="jueves" name="diasSemana" value="Jueves"> Jueves</label>
                    <label for="viernes"><input type="checkbox" id="viernes" name="diasSemana" value="Viernes"> Viernes</label>
                    <label for="sábado"><input type="checkbox" id="sábado" name="diasSemana" value="Sábado"> Sábado</label>
                    <label for="domingo"><input type="checkbox" id="domingo" name="diasSemana" value="Domingo"> Domingo</label>
                </div>
            </div>
            
            <input type="hidden" id="diasSemana" name="diasSemana">
            
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
</body>
</html>