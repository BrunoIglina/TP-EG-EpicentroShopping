<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/queries/promociones.queries.php';
require_once __DIR__ . '/queries/locales.queries.php';

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
    case 'crear_promocion':
        procesar_crear_promocion();
        break;
    default:
        header("Location: index.php?vista=dueno_panel");
        exit();
}

function procesar_eliminar_promo()
{
    $promo_id = intval($_POST['promo_id'] ?? 0);

    if ($promo_id <= 0) {
        $_SESSION['error'] = "ID de promoción no válido.";
        header("Location: index.php?vista=dueno_promociones");
        exit();
    }

    if (eliminar_promocion_dueno($promo_id, $_SESSION['user_id'])) {
        $_SESSION['success'] = "La promoción fue eliminada correctamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al eliminar la promoción.";
    }

    header("Location: index.php?vista=dueno_promociones");
    exit();
}

function procesar_gestionar_solicitud()
{
    $promo_id = intval($_POST['promo_id'] ?? 0);
    $cliente_id = intval($_POST['cliente_id'] ?? 0);
    $estado_nuevo = $_POST['estado_nuevo'] ?? '';

    if ($promo_id <= 0 || $cliente_id <= 0 || !in_array($estado_nuevo, ['aceptar', 'rechazar'])) {
        $_SESSION['error'] = "Datos de solicitud no válidos.";
        header("Location: index.php?vista=dueno_solicitudes");
        exit();
    }

    $estado_db = ($estado_nuevo === 'aceptar') ? 'aceptada' : 'rechazada';

    if (gestionar_solicitud_query($promo_id, $cliente_id, $estado_db)) {
        $_SESSION['success'] = "La solicitud fue " . $estado_db . " correctamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al gestionar la solicitud.";
    }

    header("Location: index.php?vista=dueno_solicitudes");
    exit();
}

function procesar_crear_promocion()
{
    $local_id = intval($_POST['local_id'] ?? 0);
    $textoPromo = trim($_POST['textoPromo'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $categoriaCliente = $_POST['categoriaCliente'] ?? '';
    $diasArray = $_POST['diasSemana'] ?? [];
    $diasSemana = implode(", ", $diasArray);

    if ($local_id <= 0 || empty($textoPromo) || empty($fecha_inicio) || empty($fecha_fin) || empty($diasSemana) || empty($categoriaCliente)) {
        $_SESSION['error'] = "Por favor, completá todos los campos y seleccioná al menos un día.";
        header("Location: index.php?vista=dueno_promocion_agregar");
        exit();
    }

    if (crear_promocion_query($local_id, $textoPromo, $fecha_inicio, $fecha_fin, $diasSemana, $categoriaCliente)) {
        $_SESSION['success'] = "¡Promoción agregada con éxito!";
        header("Location: index.php?vista=dueno_promociones");
    } else {
        $_SESSION['error'] = "Error en la base de datos al crear la promoción.";
        header("Location: index.php?vista=dueno_promocion_agregar");
    }
    exit();
}
