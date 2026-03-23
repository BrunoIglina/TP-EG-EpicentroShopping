<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/queries/locales.queries.php';
require_once __DIR__ . '/queries/novedades.queries.php';
require_once __DIR__ . '/queries/promociones.queries.php';
require_once __DIR__ . '/queries/usuarios.queries.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'Administrador') {
    $_SESSION['error'] = "Acceso denegado. No tienes permisos de administrador.";
    header("Location: index.php");
    exit();
}

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    // Locales
    case 'eliminar_local':
        procesar_eliminar_local();
        break;
    case 'crear_local':
        procesar_crear_local();
        break;
    case 'editar_local':
        procesar_editar_local();
        break;

    // Usuarios
    case 'estado_usuario':
        procesar_estado_usuario();
        break;

    // Novedades
    case 'crear_novedad':
        procesar_crear_novedad();
        break;
    case 'editar_novedad':
        procesar_editar_novedad();
        break;
    case 'eliminar_novedad':
        procesar_eliminar_novedad();
        break;

    // Promociones
    case 'estado_promocion':
        procesar_estado_promocion();
        break;
    case 'eliminar_promocion':
        procesar_eliminar_promocion();
        break;

    default:
        header("Location: index.php?vista=admin_locales");
        exit();
}

// ============================================================
// LOCALES
// ============================================================

function procesar_eliminar_local()
{
    $local_id = intval($_POST['local_id'] ?? 0);
    if ($local_id <= 0) {
        $_SESSION['error'] = "ID de local no válido.";
        header("Location: index.php?vista=admin_locales");
        exit();
    }

    if (eliminar_local_query($local_id)) {
        $_SESSION['success'] = "El local fue eliminado correctamente del sistema.";
    } else {
        $_SESSION['error'] = "Error al eliminar. Es posible que el local tenga promociones activas u otros datos vinculados.";
    }
    header("Location: index.php?vista=admin_locales");
    exit();
}

function procesar_crear_local()
{
    if (empty($_POST['nombre_local']) || empty($_POST['ubicacion_local']) || empty($_POST['rubro_local']) || empty($_POST['email_dueño'])) {
        $_SESSION['error'] = "Por favor, complete todos los campos requeridos.";
        header("Location: index.php?vista=admin_local_agregar");
        exit();
    }

    $imagen_tmp = (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0)
        ? $_FILES['imagen_local']['tmp_name']
        : null;

    $resultado = crear_local_query(
        trim($_POST['nombre_local']),
        trim($_POST['ubicacion_local']),
        trim($_POST['rubro_local']),
        trim($_POST['email_dueño']),
        $imagen_tmp
    );

    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['message'];
        header("Location: index.php?vista=admin_locales");
    } else {
        $_SESSION['error'] = $resultado['message'];
        header("Location: index.php?vista=admin_local_agregar");
    }
    exit();
}

function procesar_editar_local()
{
    $id_local = intval($_POST['id_local'] ?? 0);
    if ($id_local <= 0 || empty($_POST['nombre_local']) || empty($_POST['ubicacion_local']) || empty($_POST['rubro_local']) || empty($_POST['id_dueño'])) {
        $_SESSION['error'] = "Por favor, complete todos los campos obligatorios.";
        header("Location: index.php?vista=admin_local_editar&id=" . urlencode($id_local));
        exit();
    }

    $imagen_tmp = (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0)
        ? $_FILES['imagen_local']['tmp_name']
        : null;

    $resultado = actualizar_local_query(
        $id_local,
        trim($_POST['nombre_local']),
        trim($_POST['nombre_antiguo_local'] ?? ''),
        trim($_POST['ubicacion_local']),
        trim($_POST['rubro_local']),
        intval($_POST['id_dueño']),
        $imagen_tmp
    );

    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['message'];
        header("Location: index.php?vista=admin_locales");
    } else {
        $_SESSION['error'] = $resultado['message'];
        header("Location: index.php?vista=admin_local_editar&id=" . urlencode($id_local));
    }
    exit();
}

// ============================================================
// NOVEDADES
// ============================================================

function procesar_crear_novedad()
{
    if (empty($_POST['titulo_novedad']) || empty($_POST['texto_novedad']) || empty($_POST['fecha_desde']) || empty($_POST['fecha_hasta']) || empty($_POST['categoria'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: index.php?vista=admin_novedad_agregar");
        exit();
    }

    $imagen_tmp = (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0)
        ? $_FILES['imagen_novedad']['tmp_name']
        : null;

    $resultado = crear_novedad_query(
        trim($_POST['titulo_novedad']),
        trim($_POST['texto_novedad']),
        $_POST['fecha_desde'],
        $_POST['fecha_hasta'],
        $_POST['categoria'],
        $imagen_tmp
    );

    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['message'];
        header("Location: index.php?vista=admin_novedades");
    } else {
        $_SESSION['error'] = $resultado['message'];
        header("Location: index.php?vista=admin_novedad_agregar");
    }
    exit();
}

function procesar_editar_novedad()
{
    $id_novedad = intval($_POST['id_novedad'] ?? 0);
    if ($id_novedad <= 0 || empty($_POST['titulo_novedad']) || empty($_POST['texto_novedad']) || empty($_POST['fecha_desde']) || empty($_POST['fecha_hasta']) || empty($_POST['categoria'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: index.php?vista=admin_novedad_editar&id=" . urlencode($id_novedad));
        exit();
    }

    $imagen_tmp = (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0)
        ? $_FILES['imagen_novedad']['tmp_name']
        : null;

    $resultado = actualizar_novedad_query(
        $id_novedad,
        trim($_POST['titulo_novedad']),
        trim($_POST['texto_novedad']),
        $_POST['fecha_desde'],
        $_POST['fecha_hasta'],
        $_POST['categoria'],
        $imagen_tmp
    );

    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['message'];
        header("Location: index.php?vista=admin_novedades");
    } else {
        $_SESSION['error'] = $resultado['message'];
        header("Location: index.php?vista=admin_novedad_editar&id=" . urlencode($id_novedad));
    }
    exit();
}

function procesar_eliminar_novedad()
{
    $novedad_id = intval($_POST['novedad_id'] ?? 0);
    if ($novedad_id > 0) {
        if (eliminar_novedad_query($novedad_id)) {
            $_SESSION['success'] = "Novedad eliminada correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la novedad.";
        }
    }
    header("Location: index.php?vista=admin_novedades");
    exit();
}

// ============================================================
// USUARIOS
// ============================================================

function procesar_estado_usuario()
{
    $usuario_id = intval($_POST['usuario_id'] ?? 0);
    $estado = intval($_POST['estado'] ?? 0);
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente';

    $vista_retorno = ($tipo_usuario === 'dueno') ? 'admin_aprobar_duenos' : 'admin_aprobar_clientes';
    $tipo_db = ($tipo_usuario === 'dueno') ? 'Dueno' : 'Cliente';

    if ($usuario_id > 0) {
        if ($estado === 1) {
            if (aprobar_usuario_query($usuario_id, $tipo_db)) {
                $_SESSION['success'] = "Usuario aprobado y habilitado en el sistema.";
            } else {
                $_SESSION['error'] = "Error en la base de datos al aprobar.";
            }
        } else {
            if (rechazar_usuario_query($usuario_id)) {
                $_SESSION['success'] = "Solicitud de usuario rechazada y eliminada.";
            } else {
                $_SESSION['error'] = "Error en la base de datos al rechazar.";
            }
        }
    } else {
        $_SESSION['error'] = "Error: No se recibió un ID de usuario válido.";
    }

    header("Location: index.php?vista=" . $vista_retorno);
    exit();
}

// ============================================================
// PROMOCIONES
// ============================================================

function procesar_eliminar_promocion()
{
    $promo_id = intval($_POST['promo_id'] ?? 0);
    if ($promo_id > 0) {
        if (eliminar_promocion_admin($promo_id)) {
            $_SESSION['success'] = "Promoción eliminada por el administrador.";
        } else {
            $_SESSION['error'] = "Error al eliminar la promoción.";
        }
    }
    header("Location: index.php?vista=admin_promociones");
    exit();
}

function procesar_estado_promocion()
{
    $promo_id = intval($_POST['promocion_id'] ?? 0);
    $accion_crud = $_POST['accion_crud'] ?? '';

    if ($promo_id <= 0) {
        $_SESSION['error'] = "ID de promoción inválido.";
        header("Location: index.php?vista=admin_promociones");
        exit();
    }

    if ($accion_crud === 'aprobar') {
        $ok = aprobar_promocion_query($promo_id);
        $ok ? $_SESSION['success'] = "Promoción aprobada exitosamente." : $_SESSION['error'] = "Error al aprobar la promoción.";
    } elseif ($accion_crud === 'rechazar') {
        $ok = rechazar_promocion_query($promo_id);
        $ok ? $_SESSION['success'] = "Promoción rechazada exitosamente." : $_SESSION['error'] = "Error al rechazar la promoción.";
    } else {
        $_SESSION['error'] = "Acción no reconocida.";
    }

    header("Location: index.php?vista=admin_promociones");
    exit();
}
