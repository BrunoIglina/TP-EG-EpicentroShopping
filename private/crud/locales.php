<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../functions/functions_usuarios.php';
require_once __DIR__ . '/../functions/functions_locales.php';
require_once __DIR__ . '/../helpers/subirImagen.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: ../../index.php");
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'create':
        crear_local();
        break;
    case 'update':
        actualizar_local();
        break;
    case 'delete':
        eliminar_local();
        break;
    default:
        die("Acción no válida");
}

function crear_local() {
    $conn = getDB();
    
    $nombre_local = trim($_POST['nombre_local'] ?? '');
    $ubicacion_local = trim($_POST['ubicacion_local'] ?? '');
    $rubro_local = trim($_POST['rubro_local'] ?? '');
    $email_dueño = trim($_POST['email_dueño'] ?? '');
    
    if (empty($nombre_local) || empty($ubicacion_local) || empty($rubro_local) || empty($email_dueño)) {
        $_SESSION['mensaje_error1'] = "Error: Todos los campos son obligatorios.";
        header("Location: ../../agregar_local.php");
        exit();
    }
    
    $stmt = $conn->prepare("SELECT id FROM locales WHERE nombre = ?");
    $stmt->bind_param("s", $nombre_local);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $_SESSION['mensaje_error1'] = "Error: Ya existe un local con ese nombre.";
        header("Location: ../../agregar_local.php");
        exit();
    }
    $stmt->close();
    
    $dueño = get_dueño_by_email($email_dueño);
    
    if (!$dueño) {
        $_SESSION['mensaje_error1'] = "Error: No se encontró un dueño con ese email.";
        header("Location: ../../agregar_local.php");
        exit();
    }
    
    $idUsuario = $dueño['id'];
    
    $stmt = $conn->prepare("INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre_local, $ubicacion_local, $rubro_local, $idUsuario);
    
    if ($stmt->execute()) {
        $id_local = $stmt->insert_id;
        $stmt->close();
        
        if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0) {
            $imagen_tmp = $_FILES['imagen_local']['tmp_name'];
            if (!subirImagen($id_local, $imagen_tmp, "locales", $conn)) {
                $delete_stmt = $conn->prepare("DELETE FROM locales WHERE id = ?");
                $delete_stmt->bind_param("i", $id_local);
                $delete_stmt->execute();
                $delete_stmt->close();
                
                $_SESSION['mensaje_error1'] = "No se pudo subir la imagen. Puede que el archivo sea demasiado grande.";
                header("Location: ../../agregar_local.php");
                exit();
            }
        }
        
        $_SESSION['success'] = "Local creado exitosamente.";
        header("Location: ../../admin_locales.php");
        exit();
    } else {
        error_log("Error al crear local: " . $stmt->error);
        $stmt->close();
        $_SESSION['mensaje_error1'] = "Error al registrar el local.";
        header("Location: ../../agregar_local.php");
        exit();
    }
}

function actualizar_local() {
    $conn = getDB();
    
    $id_local = filter_input(INPUT_POST, 'id_local', FILTER_VALIDATE_INT);
    $nombre_antiguo = trim($_POST['nombre_antiguo_local'] ?? '');
    $nombre = trim($_POST['nombre_local'] ?? '');
    $ubicacion = trim($_POST['ubicacion_local'] ?? '');
    $rubro = trim($_POST['rubro_local'] ?? '');
    $id_dueño = filter_input(INPUT_POST, 'id_dueño', FILTER_VALIDATE_INT);
    
    if (!$id_local || empty($nombre) || empty($ubicacion) || empty($rubro) || !$id_dueño) {
        $_SESSION['error'] = "Error: Todos los campos son obligatorios.";
        header("Location: ../../admin_locales.php");
        exit();
    }
    
    $dueño = get_dueño($id_dueño);
    
    if (!$dueño) {
        $_SESSION['error'] = "El usuario ingresado no es dueño.";
        header("Location: ../../admin_locales.php");
        exit();
    }
    
    if ($nombre_antiguo != $nombre) {
        $local = get_local_by_nombre($nombre);
        if ($local) {
            $_SESSION['error'] = "Ya existe un local con ese nombre.";
            header("Location: ../../admin_locales.php");
            exit();
        }
    }
    
    $stmt = $conn->prepare("UPDATE locales SET nombre = ?, ubicacion = ?, rubro = ?, idUsuario = ? WHERE id = ?");
    $stmt->bind_param("sssii", $nombre, $ubicacion, $rubro, $id_dueño, $id_local);
    
    if ($stmt->execute()) {
        $stmt->close();
        
        if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0) {
            $imagen_tmp = $_FILES['imagen_local']['tmp_name'];
            subirImagen($id_local, $imagen_tmp, "locales", $conn);
        }
        
        $_SESSION['success'] = "Local actualizado exitosamente.";
        header("Location: ../../admin_locales.php");
        exit();
    } else {
        error_log("Error al actualizar local: " . $stmt->error);
        $stmt->close();
        $_SESSION['error'] = "Error al actualizar el local.";
        header("Location: ../../admin_locales.php");
        exit();
    }
}

function eliminar_local() {
    $conn = getDB();
    
    $local_id = filter_input(INPUT_GET, 'local_id', FILTER_VALIDATE_INT);
    
    if (!$local_id) {
        $_SESSION['error'] = "ID de local inválido.";
        header("Location: ../../admin_locales.php");
        exit();
    }
    
    $stmt = $conn->prepare("DELETE FROM locales WHERE id = ?");
    $stmt->bind_param("i", $local_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Local eliminado exitosamente.";
    } else {
        error_log("Error al eliminar local: " . $stmt->error);
        $_SESSION['error'] = "Error al eliminar el local.";
    }
    
    $stmt->close();
    header("Location: ../../admin_locales.php");
    exit();
}