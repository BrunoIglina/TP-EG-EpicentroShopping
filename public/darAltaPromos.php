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
    <title>Alta de Promociones</title>
    <script src="../scripts/diasSem.js"></script> <!-- Incluir el archivo JavaScript -->
</head>
<body>
    <h1>Agregar Nueva Promoción</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form action="../private/controladorPromos.php" method="POST" onsubmit="return validarFechas(event)">
        <label for="local_id">Nombre del Local:</label>
        <select id="local_id" name="local_id" required>
            <option value="">Seleccione un local</option>
            <?php foreach ($locales as $local) { ?>
                <option value="<?= $local['id']; ?>"><?= $local['nombre']; ?></option>
            <?php } ?>
        </select><br><br>
        
        <label for="textoPromo">Texto de la Promoción:</label>
        <textarea id="textoPromo" name="textoPromo" required></textarea><br><br>
        
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required><br><br>
        <div id="fechaInicioError" style="color: red;"></div><br><br> 
        
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required><br><br>
        <div id="fechaFinError" style="color: red;"></div><br><br>
        
        <label>Días de la Semana:</label><br>
        <input type="checkbox" id="lunes" name="diasSemana" value="Lunes">
        <label for="lunes">Lunes</label><br>
        <input type="checkbox" id="martes" name="diasSemana" value="Martes">
        <label for="martes">Martes</label><br>
        <input type="checkbox" id="miércoles" name="diasSemana" value="Miércoles">
        <label for="miércoles">Miércoles</label><br>
        <input type="checkbox" id="jueves" name="diasSemana" value="Jueves">
        <label for="jueves">Jueves</label><br>
        <input type="checkbox" id="viernes" name="diasSemana" value="Viernes">
        <label for="viernes">Viernes</label><br>
        <input type="checkbox" id="sábado" name="diasSemana" value="Sábado">
        <label for="sábado">Sábado</label><br>
        <input type="checkbox" id="domingo" name="diasSemana" value="Domingo">
        <label for="domingo">Domingo</label><br><br>
        
        <input type="hidden" id="diasSemana" name="diasSemana">
        
        <label for="categoriaCliente">Categoría de Cliente:</label>
        <select id="categoriaCliente" name="categoriaCliente" required>
            <option value="">Seleccione una categoría</option>
            <option value="Inicial">Inicial</option>
            <option value="Medium">Medium</option>
            <option value="Premium">Premium</option>
        </select><br><br>
        
        <input type="submit" value="Agregar Promoción">
    </form>
</body>
</html>
