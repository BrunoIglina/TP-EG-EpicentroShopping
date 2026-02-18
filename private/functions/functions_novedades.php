<?php

require_once __DIR__ . '/../../config/database.php';

function get_all_novedades($limit = null, $offset = null){
    $conn = getDB();

    $qry_novedades = "SELECT * FROM novedades ";

    if ($limit !== null && $offset !== null) {
        $qry_novedades .= " LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($qry_novedades);
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        $stmt = $conn->prepare($qry_novedades);
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

function get_novedad($id){
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

function mesEnEspañol($mesIngles) {
    $meses = [
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    ];
    return $meses[$mesIngles];
}

function get_novedades_permitidas($id_usuario, $tipo_usuario, $categoria_usuario) {
    $conn = getDB();
    $today = date("Y-m-d");

    if ($tipo_usuario == 'Dueno' || $tipo_usuario == 'Administrador' || $categoria_usuario == 'Premium') {
        $qry_novedad = "SELECT * FROM novedades WHERE ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC";
        $stmt = $conn->prepare($qry_novedad);
        $stmt->bind_param("s", $today);
    } elseif ($categoria_usuario == 'Medium') {
        $qry_novedad = "SELECT * FROM novedades WHERE categoria != 'Premium' AND ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC";
        $stmt = $conn->prepare($qry_novedad);
        $stmt->bind_param("s", $today);
    } else {
        $qry_novedad = "SELECT * FROM novedades WHERE categoria = 'Inicial' AND ? BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC";
        $stmt = $conn->prepare($qry_novedad);
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
        $fecha_original = $novedad['fecha_desde'];
        $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_original);
        $fecha_formateada = $fecha_obj->format('d') . " de " . mesEnEspañol($fecha_obj->format('F')) . " de " . $fecha_obj->format('Y');
        $novedad['fecha_desde'] = "Fecha: " . $fecha_formateada;
    }
    
    return $novedades;
}

function get_total_novedades() {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM novedades");
    
    if (!$stmt->execute()) {
        error_log("Error en get_total_novedades: " . $stmt->error);
        return 0;
    }
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['total'];
}

?>
