<?php
require_once __DIR__ . '/../../config/database.php';

function get_all_locales($limit = null, $offset = null) {
    $conn = getDB();
    
    $qry_locales = "SELECT * FROM locales ORDER BY nombre";
    
    if ($limit !== null && $offset !== null) {
        $qry_locales .= " LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($qry_locales);
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        $stmt = $conn->prepare($qry_locales);
    }
    
    if (!$stmt->execute()) {
        error_log("Error en get_all_locales: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $locales = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $locales;
}

function get_local($id_local) {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT * FROM locales WHERE id = ?");
    $stmt->bind_param("i", $id_local);
    
    if (!$stmt->execute()) {
        error_log("Error en get_local: " . $stmt->error);
        return null;
    }
    
    $result = $stmt->get_result();
    $local = $result->fetch_assoc();
    $stmt->close();
    
    return $local;
}

function get_local_by_nombre($nombre) {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT * FROM locales WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    
    if (!$stmt->execute()) {
        error_log("Error en get_local_by_nombre: " . $stmt->error);
        return null;
    }
    
    $result = $stmt->get_result();
    $local = $result->fetch_assoc();
    $stmt->close();
    
    return $local;
}

function get_total_locales() {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM locales");
    
    if (!$stmt->execute()) {
        error_log("Error en get_total_locales: " . $stmt->error);
        return 0;
    }
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['total'];
}

function get_locales_solicitados() {
    $conn = getDB();
    
    $qry_promociones = "SELECT pro.local_id, COUNT(pxc.idPromocion) AS solicitudes 
                        FROM promociones_cliente pxc 
                        INNER JOIN promociones pro ON pro.id = pxc.idPromocion 
                        GROUP BY pro.local_id
                        ORDER BY solicitudes DESC
                        LIMIT 4";
    
    $stmt = $conn->prepare($qry_promociones);
    
    if (!$stmt->execute()) {
        error_log("Error en get_locales_solicitados: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $promociones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    $locales = array();
    foreach ($promociones as $promo) {
        $local = get_local($promo['local_id']);
        if ($local) {
            $locales[] = $local;
        }
    }
    
    return $locales;
}