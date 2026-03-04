<?php
require_once __DIR__ . '/../../config/database.php';

function get_dueño($idUsuario){
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

function get_dueño_by_email($email) {
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

function get_all_dueños() {
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

function get_usuario($id) {
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

function get_categorias() {
    return ['Inicial', 'Medium', 'Premium'];
}


?>