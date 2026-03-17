<?php
require_once __DIR__ . '/../config/database.php';

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

// FUNCIONES DE LOCALES
function procesar_eliminar_local()
{
  $local_id = intval($_POST['local_id'] ?? 0);
  if ($local_id <= 0) {
    $_SESSION['error'] = "ID de local no válido.";
    header("Location: index.php?vista=admin_locales");
    exit();
  }
  $conn = getDB();
  $stmt = $conn->prepare("DELETE FROM locales WHERE id = ?");
  $stmt->bind_param("i", $local_id);
  if ($stmt->execute()) {
    $_SESSION['success'] = "El local fue eliminado correctamente del sistema.";
  } else {
    $_SESSION['error'] = "Error al eliminar. Es posible que el local tenga promociones activas u otros datos vinculados.";
  }
  $stmt->close();
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
  $_POST['action'] = 'create';
  ob_start();
  include(__DIR__ . '/crud/locales.php');
  $response = ob_get_clean();

  if (stripos($response, "exito") !== false || stripos($response, "correctamente") !== false) {
    $_SESSION['success'] = "El local fue registrado con éxito.";
    header("Location: index.php?vista=admin_locales");
    exit();
  } else {
    $_SESSION['error'] = !empty($response) ? $response : "Ocurrió un error al intentar registrar el local.";
    header("Location: index.php?vista=admin_local_agregar");
    exit();
  }
}

function procesar_editar_local()
{
  $id_local = $_POST['id_local'] ?? '';
  if (empty($id_local) || empty($_POST['nombre_local']) || empty($_POST['ubicacion_local']) || empty($_POST['rubro_local']) || empty($_POST['id_dueño'])) {
    $_SESSION['error'] = "Por favor, complete todos los campos obligatorios.";
    header("Location: index.php?vista=admin_local_editar&id=" . urlencode($id_local));
    exit();
  }
  $_POST['action'] = 'update';
  ob_start();
  include(__DIR__ . '/crud/locales.php');
  $response = ob_get_clean();

  if (stripos($response, "exito") !== false || stripos($response, "correctamente") !== false || stripos($response, "actualizado") !== false) {
    $_SESSION['success'] = "El local fue modificado con éxito.";
    header("Location: index.php?vista=admin_locales");
    exit();
  } else {
    $_SESSION['error'] = !empty($response) ? $response : "Ocurrió un error al intentar modificar el local.";
    header("Location: index.php?vista=admin_local_editar&id=" . urlencode($id_local));
    exit();
  }
}

// FUNCIONES DE NOVEDADES
function procesar_crear_novedad()
{
  $_POST['action'] = 'create';
  ob_start();
  include(__DIR__ . '/crud/novedades.php');
  $response = ob_get_clean();

  if (stripos($response, "exito") !== false || stripos($response, "correctamente") !== false) {
    $_SESSION['success'] = "La novedad fue publicada con éxito.";
    header("Location: index.php?vista=admin_novedades");
  } else {
    $_SESSION['error'] = !empty($response) ? $response : "Error al publicar la novedad.";
    header("Location: index.php?vista=admin_novedad_agregar");
  }
  exit();
}

function procesar_editar_novedad()
{
  $id_novedad = $_POST['id_novedad'] ?? '';
  $_POST['action'] = 'update';
  ob_start();
  include(__DIR__ . '/crud/novedades.php');
  $response = ob_get_clean();

  if (stripos($response, "exito") !== false || stripos($response, "correctamente") !== false) {
    $_SESSION['success'] = "Novedad modificada con éxito.";
    header("Location: index.php?vista=admin_novedades");
  } else {
    $_SESSION['error'] = "Error al modificar la novedad.";
    header("Location: index.php?vista=admin_novedad_editar&id=" . urlencode($id_novedad));
  }
  exit();
}

function procesar_eliminar_novedad()
{
  $novedad_id = intval($_POST['novedad_id'] ?? 0);
  if ($novedad_id > 0) {
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
    $stmt->bind_param("i", $novedad_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = "Novedad eliminada correctamente.";
  }
  header("Location: index.php?vista=admin_novedades");
  exit();
}

// FUNCIÓN DE USUARIOS (Corregida)
function procesar_estado_usuario()
{
  $usuario_id = intval($_POST['usuario_id'] ?? 0);
  $estado = intval($_POST['estado'] ?? 0);
  $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente';

  $vista_retorno = ($tipo_usuario === 'dueno') ? 'admin_aprobar_duenos' : 'admin_aprobar_clientes';

  if ($usuario_id > 0) {
    $conn = getDB();
    if ($estado === 1) {
      $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE id = ?");
      $stmt->bind_param("i", $usuario_id);
      if ($stmt->execute()) {
        $_SESSION['success'] = "Usuario aprobado y habilitado en el sistema.";
      } else {
        $_SESSION['error'] = "Error en la base de datos al aprobar.";
      }
    } else {
      $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
      $stmt->bind_param("i", $usuario_id);
      if ($stmt->execute()) {
        $_SESSION['success'] = "Solicitud de usuario rechazada y eliminada.";
      } else {
        $_SESSION['error'] = "Error en la base de datos al rechazar.";
      }
    }
    $stmt->close();
  } else {
    $_SESSION['error'] = "Error: No se recibió un ID de usuario válido.";
  }
  header("Location: index.php?vista=" . $vista_retorno);
  exit();
}

// FUNCIONES DE PROMOCIONES
function procesar_eliminar_promocion()
{
  $promo_id = intval($_POST['promo_id'] ?? 0);
  if ($promo_id > 0) {
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM promociones WHERE id = ?");
    $stmt->bind_param("i", $promo_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = "Promoción eliminada por el administrador.";
  }
  header("Location: index.php?vista=admin_promociones");
  exit();
}

function procesar_estado_promocion()
{
  $_POST['action'] = $_POST['accion_crud'] ?? '';
  ob_start();
  include(__DIR__ . '/crud/promociones.php');
  $response = ob_get_clean();

  if (stripos($response, "exito") !== false || stripos($response, "correctamente") !== false || stripos($response, "actualizado") !== false) {
    $_SESSION['success'] = "Estado de la promoción actualizado correctamente.";
  } else {
    $_SESSION['error'] = !empty($response) ? $response : "Ocurrió un error al intentar actualizar la promoción.";
  }
  header("Location: index.php?vista=admin_promociones");
  exit();
}
