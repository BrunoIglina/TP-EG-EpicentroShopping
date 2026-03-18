<?php
// private/logic/functions/functions_dueno.php

require_once __DIR__ . '/../../config/database.php';

/**
 * --- SECCIÓN 1: MIS PROMOCIONES ---
 */

function get_promociones_dueno($usuario_id, $limit, $offset) {
    $conn = getDB();
    // Acá el SQL está completo, sin puntos suspensivos
    $stmt = $conn->prepare("
        SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.diasSemana, 
               p.categoriaCliente, p.local_id, p.estadoPromo, l.nombre as local_nombre,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        INNER JOIN locales l ON p.local_id = l.id
        WHERE l.idUsuario = ?
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("iii", $usuario_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $promociones = [];
    while ($row = $result->fetch_assoc()) {
        $promociones[] = $row;
    }
    
    $stmt->close();
    return $promociones;
}

function get_total_promociones_dueno($usuario_id) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM promociones p
        INNER JOIN locales l ON p.local_id = l.id
        WHERE l.idUsuario = ?
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total = 0;
    if ($row = $result->fetch_assoc()) {
        $total = $row['total'];
    }
    
    $stmt->close();
    return $total;
}

/**
 * --- SECCIÓN 2: GESTIÓN DE SOLICITUDES ---
 */

function get_solicitudes_dueno($usuario_id, $limit, $offset) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT 
            promociones_cliente.idPromocion,
            promociones_cliente.estado,
            promociones.textoPromo, 
            promociones.fecha_inicio, 
            promociones.fecha_fin,
            promociones.diasSemana,
            locales.nombre AS local_nombre,
            usuarios.id AS idCliente,
            usuarios.email AS email
        FROM promociones_cliente 
        INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
        INNER JOIN locales ON promociones.local_id = locales.id 
        INNER JOIN usuarios ON promociones_cliente.idCliente = usuarios.id  
        WHERE locales.idUsuario = ?
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("iii", $usuario_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $solicitudes = [];
    while ($row = $result->fetch_assoc()) {
        $solicitudes[] = $row;
    }
    
    $stmt->close();
    return $solicitudes;
}

function get_total_solicitudes_dueno($usuario_id) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM promociones_cliente 
        INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
        INNER JOIN locales ON promociones.local_id = locales.id 
        INNER JOIN usuarios ON promociones_cliente.idCliente = usuarios.id  
        WHERE locales.idUsuario = ?
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total = 0;
    if ($row = $result->fetch_assoc()) {
        $total = $row['total'];
    }
    
    $stmt->close();
    return $total;
}
function get_locales_por_dueno($usuario_id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, nombre FROM locales WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $locales = [];
    while ($row = $result->fetch_assoc()) {
        $locales[] = $row;
    }
    $stmt->close();
    return $locales;
}
function get_reporte_promos_dueno($usuario_id) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT p.textoPromo, l.nombre as local_nombre,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS usos
        FROM promociones p
        INNER JOIN locales l ON p.local_id = l.id
        WHERE l.idUsuario = ?
        ORDER BY usos DESC
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reporte = [];
    while ($row = $result->fetch_assoc()) {
        $reporte[] = $row;
    }
    $stmt->close();
    return $reporte;
}
?>