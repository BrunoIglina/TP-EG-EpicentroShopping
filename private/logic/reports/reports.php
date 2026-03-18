<?php
require_once __DIR__ . '/../../config/database.php';

function getPromocionesReport($userId, $fechaInicio = null, $fechaFin = null, $estadoPromo = null)
{
    $conn = getDB();
    $sql = "SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.categoriaCliente, p.local_id, p.estadoPromo,
            (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
            FROM promociones p
            WHERE p.local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";

    $params = [$userId];
    $types = "i";

    if ($fechaInicio) {
        $sql .= " AND p.fecha_inicio >= ?";
        $params[] = $fechaInicio;
        $types .= "s";
    }
    if ($fechaFin) {
        $sql .= " AND p.fecha_fin <= ?";
        $params[] = $fechaFin;
        $types .= "s";
    }
    if ($estadoPromo) {
        $sql .= " AND p.estadoPromo = ?";
        $params[] = $estadoPromo;
        $types .= "s";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Handle prepare error
        return null;
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
