<?php
function subirImagen($id, $archivo, $tabla, $conn) {
    if (!isset($archivo) || empty($archivo)) {
        return false;
    }

    $tipoArchivo = mime_content_type($archivo);
    $tiposPermitidos = ['image/png', 'image/jpeg']; 
    if (!in_array($tipoArchivo, $tiposPermitidos)) {
        return false;
    }

    $contenido = file_get_contents($archivo);
    if ($contenido === false) {
        return false;
    }

    if ($tabla === "locales") {
        $query = "UPDATE locales SET imagen = ? WHERE id = ?";
    } elseif ($tabla === "novedades") {
        $query = "UPDATE novedades SET imagen = ? WHERE id = ?";
    } else {
        return false;
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        return false;
    }

    $stmt->bind_param("si", $contenido, $id);
    $stmt->send_long_data(0, $contenido);  

    $resultado = $stmt->execute() && $stmt->affected_rows > 0;
    $stmt->close();

    return $resultado;
}

