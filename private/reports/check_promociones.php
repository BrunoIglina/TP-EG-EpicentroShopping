<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once '../../config/database.php';

$local_id = isset($_GET['local_id']) ? intval($_GET['local_id']) : 0;

if ($local_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de local inválido']);
    exit();
}

$conn = getDB();

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM promociones WHERE local_id = ?");
$stmt->bind_param('i', $local_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

header('Content-Type: application/json');
echo json_encode([
    'has_promociones' => $row['total'] > 0,
    'total_promociones' => $row['total']
]);
?>