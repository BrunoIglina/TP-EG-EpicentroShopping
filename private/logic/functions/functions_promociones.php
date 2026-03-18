<?php

require_once __DIR__ . '/../../config/database.php';


function get_all_promociones_activas()
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

function get_promociones_pendientes($limit, $offset)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT id, textoPromo, fecha_inicio, fecha_fin FROM promociones WHERE estadoPromo = 'Pendiente' LIMIT ? OFFSET ?");
	$stmt->bind_param("ii", $limit, $offset);
	$stmt->execute();
	$result = $stmt->get_result();
	$promos = [];
	while ($row = $result->fetch_assoc()) {
		$promos[] = $row;
	}
	$stmt->close();
	return $promos;
}

function get_total_promociones_pendientes()
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM promociones WHERE estadoPromo = 'Pendiente'");
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}

function get_promociones_by_local($local_id, $limit, $offset)
{
	$conn = getDB();
	$stmt = $conn->prepare("
        SELECT 
            id AS promo_id,
            textoPromo, 
            fecha_inicio, 
            fecha_fin,
            diasSemana,
            local_id,
            categoriaCliente
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

function get_total_promociones_by_local($local_id)
{
	$conn = getDB();
	$stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM promociones 
        WHERE estadoPromo = 'Aprobada'
            AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
            AND local_id = ?
    ");
	$stmt->bind_param("i", $local_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}

function get_promociones_cliente($usuario_id, $limit, $offset)
{
	$conn = getDB();
	$stmt = $conn->prepare("
        SELECT 
            locales.nombre,
            promociones.textoPromo, 
            promociones.fecha_inicio, 
            promociones.fecha_fin,
            promociones.diasSemana,
            promociones_cliente.estado
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

function get_total_promociones_cliente($usuario_id)
{
	$conn = getDB();
	$stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM promociones_cliente 
        WHERE idCliente = ?
    ");
	$stmt->bind_param("i", $usuario_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}

function pedir_promocion($usuario_id, $promo_id)
{
	$conn = getDB();
	$stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM promociones_cliente WHERE idCliente = ? AND idPromocion = ?");
	$stmt_check->bind_param("ii", $usuario_id, $promo_id);
	$stmt_check->execute();
	$check_result = $stmt_check->get_result();
	$row = $check_result->fetch_assoc();
	$stmt_check->close();

	if ($row['count'] > 0) {
		return ['success' => false, 'message' => "Ya has solicitado esta promoción anteriormente."];
	}

	$stmt_insert = $conn->prepare("INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES (?, ?, NOW(), 'enviada')");
	$stmt_insert->bind_param("ii", $usuario_id, $promo_id);

	if ($stmt_insert->execute()) {
		$stmt_insert->close();
		return ['success' => true, 'message' => "Promoción pedida exitosamente."];
	} else {
		$error = $stmt_insert->error;
		$stmt_insert->close();
		return ['success' => false, 'message' => "Error al pedir la promoción: " . $error];
	}
}

function ya_pidio_promocion($usuario_id, $promo_id)
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

function get_promociones_dueno($user_id, $limit, $offset)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.diasSemana, p.categoriaCliente, p.local_id, p.estadoPromo, l.nombre as local_nombre,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        INNER JOIN locales l ON p.local_id = l.id
        WHERE l.idUsuario = ?
        LIMIT ? OFFSET ?");

	$stmt->bind_param("iii", $user_id, $limit, $offset);
	$stmt->execute();
	$result = $stmt->get_result();
	$promos = $result->fetch_all(MYSQLI_ASSOC);
	$stmt->close();
	return $promos;
}

function get_total_promociones_dueno($user_id)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT COUNT(*) AS total
                     FROM promociones p
                     INNER JOIN locales l ON p.local_id = l.id
                     WHERE l.idUsuario = ?");
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}
