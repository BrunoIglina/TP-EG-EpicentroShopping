<?php
// Como lo carga index.php, ya tenemos acceso a todo
require_once __DIR__ . '/../../config/database.php';

$local_id = filter_input(INPUT_GET, 'local_id', FILTER_VALIDATE_INT);
$conn = getDB();

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM promociones WHERE local_id = ?");
$stmt->bind_param('i', $local_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode(['has_promociones' => ($row['total'] > 0)]);
exit();
