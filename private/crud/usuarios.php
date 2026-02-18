<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'registrar_cliente':
        registrar_cliente();
        break;
    case 'registrar_dueno':
        registrar_dueno();
        break;
    case 'validar_token':
        validar_token();
        break;
    case 'aprobar_dueno':
        aprobar_dueno();
        break;
    case 'validar_categoria':
        validar_categoria();
        break;
    case 'aprobar_cliente':
        aprobar_cliente();
        break;
    default:
        die("Acción no válida");
}

function registrar_cliente() {
    $conn = getDB();
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        die("Email y contraseña son obligatorios");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido");
    }
    
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Error: El correo electrónico ya está registrado.";
        exit();
    }
    $stmt->close();
    
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO usuarios (email, password, tipo, validado, categoria) VALUES (?, ?, 'Cliente', 0, 'Inicial')");
    $stmt->bind_param("ss", $email, $password_hash);
    
    if ($stmt->execute()) {
        echo "Registro exitoso. Un Administrador debe validar tu cuenta.";
    } else {
        error_log("Error al registrar cliente: " . $stmt->error);
        die("Error al registrar. Contacte al administrador.");
    }
    
    $stmt->close();
}

function registrar_dueno() {
    $conn = getDB();
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        die("Email y contraseña son obligatorios");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido");
    }
    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        die("El mail ya existe, por favor pruebe con otro");
    }
    $stmt->close();
    
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO usuarios (email, password, tipo, validado) VALUES (?, ?, 'Dueno', 0)");
    $stmt->bind_param("ss", $email, $password_hash);
    
    if ($stmt->execute()) {
        echo "Registro exitoso. El Administrador debe validar su cuenta.";
    } else {
        error_log("Error al registrar dueño: " . $stmt->error);
        die("Error al registrar. Contacte al administrador.");
    }
    
    $stmt->close();
}

function validar_token() {
    $conn = getDB();
    
    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    if (empty($token) || strlen($token) > 255) {
        die("Token de validación inválido.");
    }
    
    $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE token_validacion = ? AND validado = 0");
    $stmt->bind_param("s", $token);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Cuenta validada correctamente. Ya puede iniciar sesión.";
        } else {
            echo "El token no es válido o la cuenta ya fue validada previamente.";
        }
    } else {
        error_log("Error al validar cliente: " . $stmt->error);
        echo "Ocurrió un error al validar su cuenta. Por favor, contacte al administrador.";
    }
    
    $stmt->close();
}

function aprobar_dueno() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
        header("Location: ../../login.php");
        exit();
    }
    
    $conn = getDB();
    
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        $_SESSION['error'] = "ID de usuario inválido";
        header("Location: ../../admin_aprobar_dueños.php");
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE id = ? AND tipo = 'Dueno'");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Dueño aprobado correctamente";
    } else {
        error_log("Error al aprobar dueño: " . $stmt->error);
        $_SESSION['error'] = "Error al aprobar dueño";
    }
    
    $stmt->close();
    header("Location: ../../admin_aprobar_dueños.php");
    exit();
}
function aprobar_cliente() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
        header("Location: ../../login.php");
        exit();
    }
    
    $conn = getDB();
    
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        $_SESSION['error'] = "ID de usuario inválido";
        header("Location: ../../admin_aprobar_clientes.php");
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE id = ? AND tipo = 'Cliente'");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente aprobado correctamente";
    } else {
        error_log("Error al aprobar cliente: " . $stmt->error);
        $_SESSION['error'] = "Error al aprobar cliente";
    }
    
    $stmt->close();
    header("Location: ../../admin_aprobar_clientes.php");
    exit();
}

function validar_categoria() {
    if (!isset($_SESSION['user_id'])) {
        die("Debes estar registrado para validar la categoría.");
    }
    
    $conn = getDB();
    
    $cliente_id = filter_input(INPUT_GET, 'cliente_id', FILTER_VALIDATE_INT);
    
    if (!$cliente_id) {
        die("ID de cliente inválido");
    }
    
    $stmt = $conn->prepare("SELECT usu.categoria, COUNT(pxc.idCliente) AS total_aceptadas 
                            FROM promociones_cliente pxc
                            INNER JOIN usuarios usu ON pxc.idCliente = usu.id
                            WHERE pxc.idCliente = ? AND estado = 'aceptada'
                            GROUP BY usu.categoria");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        header("Location: ../../gestion_promos.php");
        exit();
    }
    
    $row = $result->fetch_assoc();
    $stmt->close();
    
    $total_aceptadas = $row['total_aceptadas'];
    $categoria_actual = $row['categoria'];
    
    $nueva_categoria = $categoria_actual;
    if ($categoria_actual == 'Inicial' && $total_aceptadas >= 3) {
        $nueva_categoria = 'Medium';
    } else if ($categoria_actual == 'Medium' && $total_aceptadas >= 5) {
        $nueva_categoria = 'Premium';
    }
    
    if ($nueva_categoria != $categoria_actual) {
        $stmt = $conn->prepare("UPDATE usuarios SET categoria = ? WHERE id = ?");
        $stmt->bind_param("si", $nueva_categoria, $cliente_id);
        
        if ($stmt->execute()) {
            echo "Categoría del cliente actualizada a $nueva_categoria.";
        } else {
            error_log("Error al actualizar categoría: " . $stmt->error);
            echo "Error al actualizar la categoría del cliente.";
        }
        $stmt->close();
    } else {
        echo "La categoría del cliente no ha cambiado.";
    }
    
    header("Location: ../../gestion_promos.php");
    exit();
}