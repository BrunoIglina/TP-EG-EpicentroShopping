<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/subirImagen.php';

// ============================================================
// LECTURA
// ============================================================

function get_all_locales(?int $limit = null, ?int $offset = null): array
{
    $conn = getDB();
    $sql = "SELECT * FROM locales ORDER BY nombre";

    if ($limit !== null && $offset !== null) {
        $sql .= " LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        $stmt = $conn->prepare($sql);
    }

    if (!$stmt->execute()) {
        error_log("Error en get_all_locales: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    $locales = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $locales;
}

function get_local(int $id_local): ?array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM locales WHERE id = ?");
    $stmt->bind_param("i", $id_local);
    if (!$stmt->execute()) {
        error_log("Error en get_local: " . $stmt->error);
        return null;
    }
    $result = $stmt->get_result();
    $local = $result->fetch_assoc();
    $stmt->close();
    return $local;
}

function get_local_by_nombre(string $nombre): ?array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM locales WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    if (!$stmt->execute()) {
        error_log("Error en get_local_by_nombre: " . $stmt->error);
        return null;
    }
    $result = $stmt->get_result();
    $local = $result->fetch_assoc();
    $stmt->close();
    return $local;
}

function get_total_locales(): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM locales");
    if (!$stmt->execute()) {
        error_log("Error en get_total_locales: " . $stmt->error);
        return 0;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_locales_solicitados(): array
{
    $conn = getDB();
    $stmt = $conn->prepare(
        "SELECT pro.local_id, COUNT(pxc.idPromocion) AS solicitudes 
         FROM promociones_cliente pxc 
         INNER JOIN promociones pro ON pro.id = pxc.idPromocion 
         GROUP BY pro.local_id
         ORDER BY solicitudes DESC
         LIMIT 4"
    );
    if (!$stmt->execute()) {
        error_log("Error en get_locales_solicitados: " . $stmt->error);
        return [];
    }
    $result = $stmt->get_result();
    $promociones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $locales = [];
    foreach ($promociones as $promo) {
        $local = get_local($promo['local_id']);
        if ($local) {
            $locales[] = $local;
        }
    }
    return $locales;
}

function get_locales_por_dueno(int $usuario_id): array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, nombre FROM locales WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $locales = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $locales;
}

// ============================================================
// ESCRITURA
// ============================================================

function crear_local_query(string $nombre, string $ubicacion, string $rubro, string $email_dueño, ?string $imagen_tmp): array
{
    require_once __DIR__ . '/usuarios.queries.php';
    $conn = getDB();

    // Verificar nombre duplicado
    $stmt = $conn->prepare("SELECT id FROM locales WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => "Ya existe un local con ese nombre."];
    }
    $stmt->close();

    // Buscar dueño por email
    $dueño = get_dueño_by_email($email_dueño);
    if (!$dueño) {
        return ['success' => false, 'message' => "No se encontró un dueño con ese email."];
    }
    $idUsuario = $dueño['id'];

    $stmt = $conn->prepare("INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $ubicacion, $rubro, $idUsuario);

    if (!$stmt->execute()) {
        error_log("Error al crear local: " . $stmt->error);
        $stmt->close();
        return ['success' => false, 'message' => "Error al registrar el local."];
    }

    $id_local = $stmt->insert_id;
    $stmt->close();

    if ($imagen_tmp) {
        if (!subirImagen($id_local, $imagen_tmp, "locales", $conn)) {
            $del = $conn->prepare("DELETE FROM locales WHERE id = ?");
            $del->bind_param("i", $id_local);
            $del->execute();
            $del->close();
            return ['success' => false, 'message' => "No se pudo subir la imagen. Puede que el archivo sea demasiado grande."];
        }
    }

    return ['success' => true, 'message' => "Local creado exitosamente."];
}

function actualizar_local_query(int $id, string $nombre, string $nombre_antiguo, string $ubicacion, string $rubro, int $id_dueño, ?string $imagen_tmp): array
{
    require_once __DIR__ . '/usuarios.queries.php';
    $conn = getDB();

    // Verificar que el dueño existe
    $dueño = get_dueño($id_dueño);
    if (!$dueño) {
        return ['success' => false, 'message' => "El usuario ingresado no es dueño."];
    }

    // Verificar nombre duplicado solo si cambió
    if ($nombre_antiguo !== $nombre) {
        $local = get_local_by_nombre($nombre);
        if ($local) {
            return ['success' => false, 'message' => "Ya existe un local con ese nombre."];
        }
    }

    $stmt = $conn->prepare("UPDATE locales SET nombre = ?, ubicacion = ?, rubro = ?, idUsuario = ? WHERE id = ?");
    $stmt->bind_param("sssii", $nombre, $ubicacion, $rubro, $id_dueño, $id);

    if (!$stmt->execute()) {
        error_log("Error al actualizar local: " . $stmt->error);
        $stmt->close();
        return ['success' => false, 'message' => "Error al actualizar el local."];
    }
    $stmt->close();

    if ($imagen_tmp) {
        subirImagen($id, $imagen_tmp, "locales", $conn);
    }

    return ['success' => true, 'message' => "Local actualizado exitosamente."];
}

function eliminar_local_query(int $id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM locales WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al eliminar local: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}
