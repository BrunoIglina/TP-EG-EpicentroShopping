<?php
require_once __DIR__ . '/../config/database.php';

// Approve a business owner (dueño) by setting validado flag
$conn = getDB();
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo "ID inválido.";
    exit();
}

$sql = "UPDATE usuarios SET validado = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Dueño aprobado correctamente.";
    header("Location: ../admin_aprobar_dueños.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
