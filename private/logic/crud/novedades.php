<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../functions/functions_novedades.php';
require_once __DIR__ . '/../helpers/subirImagen.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
	header("Location: ../../index.php?vista=login");
	exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
	case 'create':
		crear_novedad();
		break;
	case 'update':
		actualizar_novedad();
		break;
	case 'delete':
		eliminar_novedad();
		break;
	default:
		die("Acción no válida");
}

function crear_novedad()
{
	$conn = getDB();

	$titulo_novedad = trim($_POST['titulo_novedad'] ?? '');
	$texto_novedad = trim($_POST['texto_novedad'] ?? '');
	$fecha_desde = $_POST['fecha_desde'] ?? '';
	$fecha_hasta = $_POST['fecha_hasta'] ?? '';
	$categoria = $_POST['categoria'] ?? '';

	if (empty($titulo_novedad) || empty($texto_novedad) || empty($fecha_desde) || empty($fecha_hasta) || empty($categoria)) {
		$_SESSION['error'] = "Todos los campos son obligatorios.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}

	$stmt = $conn->prepare("SELECT id FROM novedades WHERE tituloNovedad = ?");
	$stmt->bind_param("s", $titulo_novedad);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$stmt->close();
		$_SESSION['error'] = "Error: Ya existe una novedad con ese título.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}
	$stmt->close();

	$today = date("Y-m-d");

	if ($fecha_desde < $today) {
		$_SESSION['error'] = "La fecha desde no puede ser anterior a hoy.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}

	if ($fecha_hasta < $today) {
		$_SESSION['error'] = "La fecha hasta ingresada ya caducó.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}

	if ($fecha_hasta < $fecha_desde) {
		$_SESSION['error'] = "La fecha hasta no puede ser anterior a la fecha desde.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}

	// --- FIX APLICADO ACÁ ---
	// Mandamos un string vacío temporalmente a la columna "imagen" para que MySQL no se queje
	$imagen_temp = '';
	$stmt = $conn->prepare("INSERT INTO novedades (tituloNovedad, textoNovedad, fecha_desde, fecha_hasta, categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssss", $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria, $imagen_temp);

	if ($stmt->execute()) {
		$id_novedad = $stmt->insert_id;
		$stmt->close();

		if (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0) {
			$imagen_tmp = $_FILES['imagen_novedad']['tmp_name'];
			if (!subirImagen($id_novedad, $imagen_tmp, "novedades", $conn)) {
				$delete_stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
				$delete_stmt->bind_param("i", $id_novedad);
				$delete_stmt->execute();
				$delete_stmt->close();

				$_SESSION['error'] = "No se pudo subir la imagen. Puede que el archivo sea demasiado grande.";
				header("Location: ../../index.php?vista=admin_novedad_agregar");
				exit();
			}
		}

		$_SESSION['success'] = "Novedad creada con éxito.";
		header("Location: ../../index.php?vista=admin_novedades");
		exit();
	} else {
		error_log("Error al crear novedad: " . $stmt->error);
		$stmt->close();
		$_SESSION['error'] = "Error al crear la novedad.";
		header("Location: ../../index.php?vista=admin_novedad_agregar");
		exit();
	}
}

function actualizar_novedad()
{
	$conn = getDB();

	$id_novedad = filter_input(INPUT_POST, 'id_novedad', FILTER_VALIDATE_INT);
	$titulo_novedad = trim($_POST['titulo_novedad'] ?? '');
	$texto_novedad = trim($_POST['texto_novedad'] ?? '');
	$fecha_desde = $_POST['fecha_desde'] ?? '';
	$fecha_hasta = $_POST['fecha_hasta'] ?? '';
	$categoria = $_POST['categoria'] ?? '';

	if (!$id_novedad || empty($titulo_novedad) || empty($texto_novedad) || empty($fecha_desde) || empty($fecha_hasta) || empty($categoria)) {
		$_SESSION['error'] = "Todos los campos son obligatorios.";
		// Si hay error pero tenemos el ID, lo mandamos de vuelta al formulario de edición
		if ($id_novedad) {
			header("Location: ../../index.php?vista=admin_novedad_editar&id=$id_novedad");
		} else {
			header("Location: ../../index.php?vista=admin_novedades");
		}
		exit();
	}

	if ($fecha_hasta < $fecha_desde) {
		$_SESSION['error'] = "La fecha hasta no puede ser anterior a la fecha desde.";
		header("Location: ../../index.php?vista=admin_novedad_editar&id=$id_novedad");
		exit();
	}

	$stmt = $conn->prepare("UPDATE novedades SET tituloNovedad = ?, textoNovedad = ?, fecha_desde = ?, fecha_hasta = ?, categoria = ? WHERE id = ?");
	$stmt->bind_param("sssssi", $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria, $id_novedad);

	if ($stmt->execute()) {
		$stmt->close();

		if (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0) {
			$imagen_tmp = $_FILES['imagen_novedad']['tmp_name'];
			subirImagen($id_novedad, $imagen_tmp, "novedades", $conn);
		}

		$_SESSION['success'] = "Novedad actualizada con éxito.";
		header("Location: ../../index.php?vista=admin_novedades");
		exit();
	} else {
		error_log("Error al actualizar novedad: " . $stmt->error);
		$stmt->close();
		$_SESSION['error'] = "Error al actualizar la novedad.";
		header("Location: ../../index.php?vista=admin_novedad_editar&id=$id_novedad");
		exit();
	}
}

function eliminar_novedad()
{
	$conn = getDB();

	$novedad_id = filter_input(INPUT_POST, 'novedad_id', FILTER_VALIDATE_INT);

	if (!$novedad_id) {
		$_SESSION['error'] = "ID de novedad inválido.";
		header("Location: ../../index.php?vista=admin_novedades");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
	$stmt->bind_param("i", $novedad_id);

	if ($stmt->execute()) {
		$_SESSION['success'] = "Novedad eliminada con éxito.";
	} else {
		error_log("Error al eliminar novedad: " . $stmt->error);
		$_SESSION['error'] = "Error al eliminar la novedad.";
	}

	$stmt->close();
	header("Location: ../../index.php?vista=admin_novedades");
	exit();
}
