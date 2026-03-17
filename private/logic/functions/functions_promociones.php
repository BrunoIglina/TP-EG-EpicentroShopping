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
