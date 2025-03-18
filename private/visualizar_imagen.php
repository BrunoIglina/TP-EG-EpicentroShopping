<?php
include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');


$local_id = isset($_GET['local_id']) ? $_GET['local_id'] : null;
$novedad_id = isset($_GET['novedad_id']) ? $_GET['novedad_id'] : null;

if ($local_id) {
    $query = "SELECT imagen FROM locales WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $local_id);
} elseif ($novedad_id) {
    $query = "SELECT imagen FROM novedades WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $novedad_id);
} else {
    echo "No se ha proporcionado un ID válido.";
    exit();
}

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($imagen);
$stmt->fetch();

if ($imagen) {
    header("Content-Type: image/png, image/jpg");
    echo $imagen;
} else {
    echo "No se encontró la imagen.";
}

$stmt->close();
?>
