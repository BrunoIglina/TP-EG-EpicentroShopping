<?php
// private/logic/dueno.controller.php
require_once __DIR__ . '/../config/database.php';

// Seguridad: Solo dueños pueden acceder a estas acciones
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'Dueno') {
    $_SESSION['error'] = "Acceso denegado. No tienes permisos de dueño.";
    header("Location: index.php");
    exit();
}

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'eliminar_promo':
        procesar_eliminar_promo();
        break;
    case 'gestionar_solicitud':
        procesar_gestionar_solicitud();
        break;
    case 'crear_promocion': // Esta la usarás para tu formulario de alta
        procesar_crear_promocion();
        break;
    default:
        // Si no se reconoce la acción, volvemos al panel del dueño
        header("Location: index.php?vista=dueno_panel");
        exit();
}

// --- FUNCIONES ---

function procesar_eliminar_promo() {
    $promo_id = intval($_POST['promo_id'] ?? 0);
    
    if ($promo_id > 0) {
        $conn = getDB();
        // Hacemos el borrado acá mismo para que el CRUD viejo no nos secuestre la página
        $stmt = $conn->prepare("DELETE FROM promociones WHERE id = ?");
        $stmt->bind_param("i", $promo_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "La promoción fue eliminada correctamente.";
        } else {
            $_SESSION['error'] = "Ocurrió un error al eliminar la promoción.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "ID de promoción no válido.";
    }
    
    // Ahora sí, lo mandamos adonde nosotros queremos
    header("Location: index.php?vista=dueno_promociones");
    exit();
}

function procesar_gestionar_solicitud() {
    $promo_id = intval($_POST['promo_id'] ?? 0);
    $cliente_id = intval($_POST['cliente_id'] ?? 0);
    $estado_nuevo = $_POST['estado_nuevo'] ?? ''; // 'aceptar' o 'rechazar'

    if ($promo_id > 0 && $cliente_id > 0 && ($estado_nuevo === 'aceptar' || $estado_nuevo === 'rechazar')) {
        
        $estado_db = ($estado_nuevo === 'aceptar') ? 'aceptada' : 'rechazada';
        
        $conn = getDB();
        // Actualizamos el estado de la solicitud directo en la base
        $stmt = $conn->prepare("UPDATE promociones_cliente SET estado = ? WHERE idPromocion = ? AND idCliente = ?");
        $stmt->bind_param("sii", $estado_db, $promo_id, $cliente_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "La solicitud fue " . $estado_db . " correctamente.";
        } else {
            $_SESSION['error'] = "Ocurrió un error al gestionar la solicitud.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Datos de solicitud no válidos.";
    }
    
    // Y volvemos al panel de solicitudes limpio
    header("Location: index.php?vista=dueno_solicitudes");
    exit();
}

function procesar_crear_promocion() {
    $local_id = intval($_POST['local_id'] ?? 0);
    $textoPromo = $_POST['textoPromo'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $categoriaCliente = $_POST['categoriaCliente'] ?? '';
    
    // Procesamos el array de días a un string
    $diasArray = $_POST['diasSemana'] ?? [];
    $diasSemana = implode(", ", $diasArray);

    if ($local_id <= 0 || empty($textoPromo) || empty($fecha_inicio) || empty($fecha_fin) || empty($diasSemana) || empty($categoriaCliente)) {
        $_SESSION['error'] = "Por favor, completá todos los campos y seleccioná al menos un día.";
        header("Location: index.php?vista=dueno_promocion_agregar");
        exit();
    }

    $conn = getDB();
    $estadoPromo = 'Pendiente';

    $stmt = $conn->prepare("INSERT INTO promociones (textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, local_id, estadoPromo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    // El tipo 'Medium' en tu HTML original dice "Medium", asegurate que en la DB sea así o corregilo a "Medio"
    $stmt->bind_param("sssssis", $textoPromo, $fecha_inicio, $fecha_fin, $diasSemana, $categoriaCliente, $local_id, $estadoPromo);

    if ($stmt->execute()) {
        $_SESSION['success'] = "¡Promoción agregada con éxito!";
        header("Location: index.php?vista=dueno_promociones");
    } else {
        $_SESSION['error'] = "Error en la base de datos al crear la promoción.";
        header("Location: index.php?vista=dueno_promocion_agregar");
    }
    
    $stmt->close();
    exit();
}