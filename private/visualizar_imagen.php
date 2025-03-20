<?php
ob_start(); 
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/../env/shopping_db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conn)) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

$local_id = $_GET['local_id'] ?? null;
$novedad_id = $_GET['novedad_id'] ?? null;

if ($local_id) {
    $query = "SELECT imagen FROM locales WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Error en la consulta: " . $conn->error);
    $stmt->bind_param("i", $local_id);
} elseif ($novedad_id) {
    $query = "SELECT imagen FROM novedades WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Error en la consulta: " . $conn->error);
    $stmt->bind_param("i", $novedad_id);
} else {
    die("No se ha proporcionado un ID válido.");
}

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($imagen);
$stmt->fetch();

if (!$imagen) {
    die("No se encontró la imagen.");
}


ob_clean();
header("Content-Type: image/jpeg"); 
echo $imagen;

$stmt->close();
ob_end_flush();
?>
