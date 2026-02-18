<?php

require_once __DIR__ . '/../../config/database.php';


function get_all_promociones_activas() {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT * FROM promociones WHERE estadoPromo = 'Aprobada' ORDER BY fecha_fin DESC");
    
    if (!$stmt->execute()) {
        error_log("Error en get_all_promociones_activas: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $promociones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $promociones;
}
?>