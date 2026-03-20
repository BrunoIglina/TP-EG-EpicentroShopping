<?php
require_once __DIR__ . '/../../config/database.php';

// ============================================================
// LECTURA
// ============================================================

function get_all_promociones_activas(): array
{
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

function get_promociones_pendientes(int $limit, int $offset): array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, textoPromo, fecha_inicio, fecha_fin FROM promociones WHERE estadoPromo = 'Pendiente' LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $promos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $promos;
}

function get_total_promociones_pendientes(): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM promociones WHERE estadoPromo = 'Pendiente'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_promociones_by_local(int $local_id, int $limit, int $offset): array
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT id AS promo_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, local_id, categoriaCliente
        FROM promociones 
        WHERE estadoPromo = 'Aprobada'
          AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
          AND local_id = ?
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("iii", $local_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $promos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $promos;
}

function get_total_promociones_by_local(int $local_id): int
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total FROM promociones 
        WHERE estadoPromo = 'Aprobada'
          AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
          AND local_id = ?
    ");
    $stmt->bind_param("i", $local_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_promociones_cliente(int $usuario_id, int $limit, int $offset): array
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT locales.nombre, promociones.textoPromo, promociones.fecha_inicio, 
               promociones.fecha_fin, promociones.diasSemana, promociones_cliente.estado
        FROM promociones_cliente 
        INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
        INNER JOIN locales ON promociones.local_id = locales.id
        WHERE promociones_cliente.idCliente = ?
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("iii", $usuario_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $promos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $promos;
}

function get_total_promociones_cliente(int $usuario_id): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM promociones_cliente WHERE idCliente = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

// Versión canónica de get_promociones_dueno (de functions_dueno.php — más completa)
function get_promociones_dueno(int $usuario_id, int $limit, int $offset): array
{
    $conn = getDB();
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
    $promociones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $promociones;
}

function get_total_promociones_dueno(int $usuario_id): int
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total FROM promociones p
        INNER JOIN locales l ON p.local_id = l.id
        WHERE l.idUsuario = ?
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_solicitudes_dueno(int $usuario_id, int $limit, int $offset): array
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT promociones_cliente.idPromocion, promociones_cliente.estado,
               promociones.textoPromo, promociones.fecha_inicio, promociones.fecha_fin, promociones.diasSemana,
               locales.nombre AS local_nombre,
               usuarios.id AS idCliente, usuarios.email AS email
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
    $solicitudes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $solicitudes;
}

function get_total_solicitudes_dueno(int $usuario_id): int
{
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total FROM promociones_cliente 
        INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
        INNER JOIN locales ON promociones.local_id = locales.id 
        WHERE locales.idUsuario = ?
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_reporte_promos_dueno(int $usuario_id): array
{
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
    $reporte = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $reporte;
}

function ya_pidio_promocion(int $usuario_id, int $promo_id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM promociones_cliente WHERE idCliente = ? AND idPromocion = ?");
    $stmt->bind_param("ii", $usuario_id, $promo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total'] > 0;
}

// ============================================================
// ESCRITURA
// ============================================================

function crear_promocion_query(int $local_id, string $texto, string $fecha_inicio, string $fecha_fin, string $dias, string $categoria): bool
{
    $conn = getDB();
    $estado = 'Pendiente';
    $stmt = $conn->prepare("INSERT INTO promociones (local_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, estadoPromo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $local_id, $texto, $fecha_inicio, $fecha_fin, $dias, $categoria, $estado);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al crear promoción: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function eliminar_promocion_dueno(int $promo_id, int $usuario_id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM promociones WHERE id = ? AND local_id IN (SELECT id FROM locales WHERE idUsuario = ?)");
    $stmt->bind_param("ii", $promo_id, $usuario_id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al eliminar promoción (dueño): " . $stmt->error);
    }
    $stmt->close();
    return $result && $stmt->affected_rows > 0;
}

function eliminar_promocion_admin(int $promo_id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM promociones WHERE id = ?");
    $stmt->bind_param("i", $promo_id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al eliminar promoción (admin): " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function aprobar_promocion_query(int $promo_id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE promociones SET estadoPromo = 'Aprobada' WHERE id = ?");
    $stmt->bind_param("i", $promo_id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al aprobar promoción: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function rechazar_promocion_query(int $promo_id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE promociones SET estadoPromo = 'Denegada' WHERE id = ?");
    $stmt->bind_param("i", $promo_id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al rechazar promoción: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function gestionar_solicitud_query(int $promo_id, int $cliente_id, string $estado): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE promociones_cliente SET estado = ? WHERE idPromocion = ? AND idCliente = ?");
    $stmt->bind_param("sii", $estado, $promo_id, $cliente_id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al gestionar solicitud: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function pedir_promocion(int $usuario_id, int $promo_id): array
{
    $conn = getDB();

    $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM promociones_cliente WHERE idCliente = ? AND idPromocion = ?");
    $stmt_check->bind_param("ii", $usuario_id, $promo_id);
    $stmt_check->execute();
    $row = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    if ($row['count'] > 0) {
        return ['success' => false, 'message' => "Ya has solicitado esta promoción anteriormente."];
    }

    $stmt = $conn->prepare("INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES (?, ?, NOW(), 'enviada')");
    $stmt->bind_param("ii", $usuario_id, $promo_id);

    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => "Promoción pedida exitosamente."];
    }

    $error = $stmt->error;
    $stmt->close();
    return ['success' => false, 'message' => "Error al pedir la promoción: " . $error];
}
