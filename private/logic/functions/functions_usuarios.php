<?php
require_once __DIR__ . '/../../config/database.php';

function get_dueño($idUsuario)
{
	$conn = getDB();

	$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ? AND tipo = 'Dueno'");
	$stmt->bind_param("i", $idUsuario);

	if (!$stmt->execute()) {
		error_log("Error en get_dueño: " . $stmt->error);
		return null;
	}

	$result = $stmt->get_result();
	$dueño = $result->fetch_assoc();
	$stmt->close();

	return $dueño;
}

function get_dueño_by_email($email)
{
	$conn = getDB();

	$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND tipo = 'Dueno'");
	$stmt->bind_param("s", $email);

	if (!$stmt->execute()) {
		error_log("Error en get_dueño_by_email: " . $stmt->error);
		return null;
	}

	$result = $stmt->get_result();
	$dueño = $result->fetch_assoc();
	$stmt->close();

	return $dueño;
}

function get_all_dueños()
{
	$conn = getDB();

	$stmt = $conn->prepare("SELECT * FROM usuarios WHERE tipo = 'Dueno'");

	if (!$stmt->execute()) {
		error_log("Error en get_all_dueños: " . $stmt->error);
		return [];
	}

	$result = $stmt->get_result();
	$dueños = $result->fetch_all(MYSQLI_ASSOC);
	$stmt->close();

	return $dueños;
}

function get_usuario($id)
{
	$conn = getDB();

	$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
	$stmt->bind_param("i", $id);

	if (!$stmt->execute()) {
		error_log("Error en get_usuario: " . $stmt->error);
		return null;
	}

	$result = $stmt->get_result();
	$usuario = $result->fetch_assoc();
	$stmt->close();

	return $usuario;
}

function get_categorias()
{
	return ['Inicial', 'Medium', 'Premium'];
}

function get_usuarios_pendientes($tipo, $limit, $offset)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT id, email FROM usuarios WHERE tipo = ? AND validado = 0 LIMIT ? OFFSET ?");
	$stmt->bind_param("sii", $tipo, $limit, $offset);
	$stmt->execute();
	$result = $stmt->get_result();
	$usuarios = [];
	while ($row = $result->fetch_assoc()) {
		$usuarios[] = $row;
	}
	$stmt->close();
	return $usuarios;
}

function get_total_usuarios_pendientes($tipo)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE tipo = ? AND validado = 0");
	$stmt->bind_param("s", $tipo);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}

function get_total_promociones_usadas_cliente($usuario_id)
{
	$conn = getDB();
	$stmt = $conn->prepare("SELECT COUNT(*) as total FROM promociones_cliente WHERE idCliente = ?");
	$stmt->bind_param('i', $usuario_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$stmt->close();
	return $row['total'];
}
