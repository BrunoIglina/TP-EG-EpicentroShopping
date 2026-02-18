<?php
require_once __DIR__ . '/../../config/database.php';

$conn = getDB();

$local_id = filter_input(INPUT_GET, 'local_id', FILTER_VALIDATE_INT);
$novedad_id = filter_input(INPUT_GET, 'novedad_id', FILTER_VALIDATE_INT);

if ($local_id) {
    $stmt = $conn->prepare("SELECT imagen FROM locales WHERE id = ?");
    $stmt->bind_param("i", $local_id);
} elseif ($novedad_id) {
    $stmt = $conn->prepare("SELECT imagen FROM novedades WHERE id = ?");
    $stmt->bind_param("i", $novedad_id);
} else {
    http_response_code(400);
    die("ID no proporcionado.");
}

if (!$stmt->execute()) {
    error_log("Error al obtener imagen: " . $stmt->error);
    http_response_code(500);
    die("Error al cargar imagen.");
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row || empty($row['imagen'])) {
    http_response_code(404);
    die("Imagen no encontrada.");
}

header("Content-Type: image/jpeg");
header("Cache-Control: public, max-age=31536000");
echo $row['imagen'];