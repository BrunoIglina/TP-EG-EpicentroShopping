<?php
require_once __DIR__ . '/../../config/database.php';

// ============================================================
// LECTURA
// ============================================================

function get_usuario(int $id): ?array
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

function get_dueño(int $idUsuario): ?array
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

function get_dueño_by_email(string $email): ?array
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

function get_all_dueños(): array
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

function get_usuarios_pendientes(string $tipo, int $limit, int $offset): array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, email FROM usuarios WHERE tipo = ? AND validado = 0 LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $tipo, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $usuarios;
}

function get_total_usuarios_pendientes(string $tipo): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE tipo = ? AND validado = 0");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_categorias(): array
{
    return ['Inicial', 'Medium', 'Premium'];
}

function get_total_promociones_usadas_cliente(int $usuario_id): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM promociones_cliente WHERE idCliente = ?");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

// ============================================================
// ESCRITURA
// ============================================================

function registrar_cliente_query(string $email, string $password): array
{
    $conn = getDB();

    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $stmt_check->close();
        return ['success' => false, 'message' => "El correo electrónico ya está registrado."];
    }
    $stmt_check->close();

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO usuarios (email, password, tipo, validado, categoria) VALUES (?, ?, 'Cliente', 0, 'Inicial')");
    $stmt->bind_param("ss", $email, $password_hash);

    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => "Registro exitoso. Un Administrador debe validar tu cuenta."];
    }

    error_log("Error al registrar cliente: " . $stmt->error);
    $stmt->close();
    return ['success' => false, 'message' => "Error al registrar. Contacte al administrador."];
}

function registrar_dueno_query(string $email, string $password): array
{
    $conn = getDB();

    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $stmt_check->close();
        return ['success' => false, 'message' => "El mail ya existe, por favor pruebe con otro."];
    }
    $stmt_check->close();

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO usuarios (email, password, tipo, validado) VALUES (?, ?, 'Dueno', 0)");
    $stmt->bind_param("ss", $email, $password_hash);

    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => "Registro exitoso. El Administrador debe validar su cuenta."];
    }

    error_log("Error al registrar dueño: " . $stmt->error);
    $stmt->close();
    return ['success' => false, 'message' => "Error al registrar. Contacte al administrador."];
}

function validar_token_query(string $token): array
{
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE token_validacion = ? AND validado = 0");
    $stmt->bind_param("s", $token);

    if ($stmt->execute()) {
        $affected = $stmt->affected_rows;
        $stmt->close();
        if ($affected > 0) {
            return ['success' => true, 'message' => "Cuenta validada correctamente. Ya puede iniciar sesión."];
        }
        return ['success' => false, 'message' => "El token no es válido o la cuenta ya fue validada previamente."];
    }

    error_log("Error al validar token: " . $stmt->error);
    $stmt->close();
    return ['success' => false, 'message' => "Ocurrió un error al validar su cuenta."];
}

function aprobar_usuario_query(int $id, string $tipo): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE usuarios SET validado = 1 WHERE id = ? AND tipo = ?");
    $stmt->bind_param("is", $id, $tipo);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al aprobar usuario: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function rechazar_usuario_query(int $id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al rechazar usuario: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}

function cambiar_password_query(int $id, string $nueva_password): bool
{
    $conn = getDB();
    $hash = password_hash($nueva_password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hash, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function cambiar_password_por_email_query(string $email, string $nueva_password): bool
{
    $conn = getDB();
    $hash = password_hash($nueva_password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
    $stmt->bind_param('ss', $hash, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
