<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'create':
        crear_promocion();
        break;
    case 'delete':
        eliminar_promocion();
        break;
    case 'gestionar_solicitud':
        gestionar_solicitud_cliente();
        break;
    case 'aprobar':
        aprobar_promocion_admin();
        break;
    case 'rechazar':
        rechazar_promocion_admin();
        break;
    default:
        die("Acción no válida");
}

function crear_promocion() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
        header("Location: ../../index.php");
        exit();
    }
    
    $conn = getDB();
    $errores = [];
    
    $local_id = filter_input(INPUT_POST, 'local_id', FILTER_VALIDATE_INT);
    $textoPromo = trim($_POST['textoPromo'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $categoriaCliente = $_POST['categoriaCliente'] ?? '';
    $estadoPromo = 'Pendiente';
    
    if (!$local_id) {
        $errores[] = "Debe seleccionar un local.";
    }
    
    if (empty($textoPromo)) {
        $errores[] = "Debe ingresar un texto de promoción.";
    }
    
    $hoy = date('Y-m-d');
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $errores[] = "Debe ingresar ambas fechas.";
    } elseif ($fecha_inicio < $hoy) {
        $errores[] = "La fecha de inicio no puede ser anterior a hoy.";
    } elseif ($fecha_fin < $fecha_inicio) {
        $errores[] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
    }
    
    $categoriasValidas = ["Inicial", "Medium", "Premium"];
    if (!in_array($categoriaCliente, $categoriasValidas)) {
        $errores[] = "Debe seleccionar una categoría válida.";
    }
    
    if (empty($_POST['diasSemana'])) {
        $errores[] = "Debe seleccionar al menos un día de la semana.";
        $diasSemanaStr = "";
    } else {
        $diasSemanaStr = implode(",", $_POST['diasSemana']);
    }
    
    if (!empty($errores)) {
        $_SESSION['error'] = implode("<br>", $errores);
        header("Location: ../../darAltaPromos.php");
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO promociones (local_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, estadoPromo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $local_id, $textoPromo, $fecha_inicio, $fecha_fin, $diasSemanaStr, $categoriaCliente, $estadoPromo);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Promoción agregada exitosamente. El Administrador debe evaluarla.";
        header("Location: ../../misPromos.php");
    } else {
        error_log("Error al crear promoción: " . $stmt->error);
        $_SESSION['error'] = "Error al agregar la promoción.";
        header("Location: ../../darAltaPromos.php");
    }
    
    $stmt->close();
    exit();
}

function eliminar_promocion() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
        header("Location: ../../index.php");
        exit();
    }
    
    $conn = getDB();
    
    $promo_id = filter_input(INPUT_POST, 'promo_id', FILTER_VALIDATE_INT);
    
    if (!$promo_id) {
        $_SESSION['error'] = "ID de promoción inválido.";
        header("Location: ../../misPromos.php");
        exit();
    }
    
    $stmt = $conn->prepare("DELETE FROM promociones WHERE id = ? AND local_id IN (SELECT id FROM locales WHERE idUsuario = ?)");
    $stmt->bind_param("ii", $promo_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Promoción eliminada exitosamente.";
        } else {
            $_SESSION['error'] = "No se encontró la promoción o no tiene permisos para eliminarla.";
        }
    } else {
        error_log("Error al eliminar promoción: " . $stmt->error);
        $_SESSION['error'] = "Error al eliminar la promoción.";
    }
    
    $stmt->close();
    header("Location: ../../misPromos.php");
    exit();
}

function gestionar_solicitud_cliente() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
        die("Debes estar registrado como dueño para gestionar las promociones.");
    }
    
    $conn = getDB();
    
    $promo_id = filter_input(INPUT_POST, 'promo_id', FILTER_VALIDATE_INT);
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $accion = $_POST['accion'] ?? '';
    
    if (!$promo_id || !$cliente_id || !in_array($accion, ['aceptar', 'rechazar'])) {
        die("Datos inválidos.");
    }
    
    $estado = ($accion == 'aceptar') ? 'aceptada' : 'rechazada';
    
    $stmt = $conn->prepare("UPDATE promociones_cliente SET estado = ? WHERE idPromocion = ? AND idCliente = ?");
    $stmt->bind_param("sii", $estado, $promo_id, $cliente_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        
        if ($accion == 'aceptar') {
            header("Location: ../crud/usuarios.php?action=validar_categoria&cliente_id=$cliente_id");
            exit();
        } else {
            $_SESSION['success'] = "Promoción rechazada exitosamente.";
            header("Location: ../../gestion_promos.php");
            exit();
        }
    } else {
        error_log("Error al gestionar solicitud: " . $stmt->error);
        $stmt->close();
        die("Error al gestionar la promoción.");
    }
}

function aprobar_promocion_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
        header("Location: ../../index.php");
        exit();
    }
    
    $conn = getDB();
    
    $promocion_id = filter_input(INPUT_POST, 'promocion_id', FILTER_VALIDATE_INT);
    
    if (!$promocion_id) {
        $_SESSION['error'] = "ID de promoción inválido.";
        header("Location: ../../admin_promociones.php");
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE promociones SET estadoPromo = 'Aprobada' WHERE id = ?");
    $stmt->bind_param("i", $promocion_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Promoción aprobada exitosamente.";
    } else {
        error_log("Error al aprobar promoción: " . $stmt->error);
        $_SESSION['error'] = "Error al aprobar la promoción.";
    }
    
    $stmt->close();
    header("Location: ../../admin_promociones.php");
    exit();
}

function rechazar_promocion_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
        header("Location: ../../index.php");
        exit();
    }
    
    $conn = getDB();
    
    $promocion_id = filter_input(INPUT_POST, 'promocion_id', FILTER_VALIDATE_INT);
    
    if (!$promocion_id) {
        $_SESSION['error'] = "ID de promoción inválido.";
        header("Location: ../../admin_promociones.php");
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE promociones SET estadoPromo = 'Denegada' WHERE id = ?");
    $stmt->bind_param("i", $promocion_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Promoción rechazada exitosamente.";
    } else {
        error_log("Error al rechazar promoción: " . $stmt->error);
        $_SESSION['error'] = "Error al rechazar la promoción.";
    }
    
    $stmt->close();
    header("Location: ../../admin_promociones.php");
    exit();
}