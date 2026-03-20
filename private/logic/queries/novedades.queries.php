<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/subirImagen.php';

// ============================================================
// LECTURA
// ============================================================

function get_all_novedades(?int $limit = null, ?int $offset = null): array
{
    $conn = getDB();
    $sql = "SELECT * FROM novedades";

    if ($limit !== null && $offset !== null) {
        $sql .= " LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        $stmt = $conn->prepare($sql);
    }

    if (!$stmt->execute()) {
        error_log("Error en get_all_novedades: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    $novedades = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $novedades;
}

function get_novedad(int $id): ?array
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM novedades WHERE id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        error_log("Error en get_novedad: " . $stmt->error);
        return null;
    }
    $result = $stmt->get_result();
    $novedad = $result->fetch_assoc();
    $stmt->close();
    return $novedad;
}

function get_total_novedades(): int
{
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM novedades");
    if (!$stmt->execute()) {
        error_log("Error en get_total_novedades: " . $stmt->error);
        return 0;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int) $row['total'];
}

function get_novedades_permitidas(int $id_usuario, string $tipo_usuario, string $categoria_usuario): array
{
    $conn = getDB();
    $today = date("Y-m-d");

    if ($tipo_usuario === 'Dueno' || $tipo_usuario === 'Administrador' || $categoria_usuario === 'Premium') {
        $stmt = $conn->prepare("SELECT * FROM novedades WHERE ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC");
        $stmt->bind_param("s", $today);
    } elseif ($categoria_usuario === 'Medium') {
        $stmt = $conn->prepare("SELECT * FROM novedades WHERE categoria != 'Premium' AND ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC");
        $stmt->bind_param("s", $today);
    } else {
        $stmt = $conn->prepare("SELECT * FROM novedades WHERE categoria = 'Inicial' AND ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC");
        $stmt->bind_param("s", $today);
    }

    if (!$stmt->execute()) {
        error_log("Error en get_novedades_permitidas: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    $novedades = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($novedades as &$novedad) {
        $fecha_obj = DateTime::createFromFormat('Y-m-d', $novedad['fecha_desde']);
        $novedad['fecha_desde'] = "Fecha: " . $fecha_obj->format('d') . " de " . mesEnEspañol($fecha_obj->format('F')) . " de " . $fecha_obj->format('Y');
    }

    return $novedades;
}

function mesEnEspañol(string $mesIngles): string
{
    $meses = [
        'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
        'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
        'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
        'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
    ];
    return $meses[$mesIngles] ?? $mesIngles;
}

// ============================================================
// ESCRITURA
// ============================================================

function crear_novedad_query(string $titulo, string $texto, string $fecha_desde, string $fecha_hasta, string $categoria, ?string $imagen_tmp): array
{
    $conn = getDB();

    // Verificar título duplicado
    $stmt = $conn->prepare("SELECT id FROM novedades WHERE tituloNovedad = ?");
    $stmt->bind_param("s", $titulo);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => "Ya existe una novedad con ese título."];
    }
    $stmt->close();

    // Validaciones de fechas
    $today = date("Y-m-d");
    if ($fecha_desde < $today) {
        return ['success' => false, 'message' => "La fecha desde no puede ser anterior a hoy."];
    }
    if ($fecha_hasta < $today) {
        return ['success' => false, 'message' => "La fecha hasta ingresada ya caducó."];
    }
    if ($fecha_hasta < $fecha_desde) {
        return ['success' => false, 'message' => "La fecha hasta no puede ser anterior a la fecha desde."];
    }

    $imagen_temp = '';
    $stmt = $conn->prepare("INSERT INTO novedades (tituloNovedad, textoNovedad, fecha_desde, fecha_hasta, categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $titulo, $texto, $fecha_desde, $fecha_hasta, $categoria, $imagen_temp);

    if (!$stmt->execute()) {
        error_log("Error al crear novedad: " . $stmt->error);
        $stmt->close();
        return ['success' => false, 'message' => "Error al crear la novedad."];
    }

    $id_novedad = $stmt->insert_id;
    $stmt->close();

    if ($imagen_tmp) {
        if (!subirImagen($id_novedad, $imagen_tmp, "novedades", $conn)) {
            $del = $conn->prepare("DELETE FROM novedades WHERE id = ?");
            $del->bind_param("i", $id_novedad);
            $del->execute();
            $del->close();
            return ['success' => false, 'message' => "No se pudo subir la imagen. Puede que el archivo sea demasiado grande."];
        }
    }

    return ['success' => true, 'message' => "Novedad creada con éxito."];
}

function actualizar_novedad_query(int $id, string $titulo, string $texto, string $fecha_desde, string $fecha_hasta, string $categoria, ?string $imagen_tmp): array
{
    $conn = getDB();

    if ($fecha_hasta < $fecha_desde) {
        return ['success' => false, 'message' => "La fecha hasta no puede ser anterior a la fecha desde."];
    }

    $stmt = $conn->prepare("UPDATE novedades SET tituloNovedad = ?, textoNovedad = ?, fecha_desde = ?, fecha_hasta = ?, categoria = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $titulo, $texto, $fecha_desde, $fecha_hasta, $categoria, $id);

    if (!$stmt->execute()) {
        error_log("Error al actualizar novedad: " . $stmt->error);
        $stmt->close();
        return ['success' => false, 'message' => "Error al actualizar la novedad."];
    }
    $stmt->close();

    if ($imagen_tmp) {
        subirImagen($id, $imagen_tmp, "novedades", $conn);
    }

    return ['success' => true, 'message' => "Novedad actualizada con éxito."];
}

function eliminar_novedad_query(int $id): bool
{
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    if (!$result) {
        error_log("Error al eliminar novedad: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}
