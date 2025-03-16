<?php
function subirImagen($id, $archivo, $tabla, $conn) {
    if (!isset($archivo) || empty($archivo)) {
        die("Error: No se ha cargado la imagen correctamente.");
    }

    $tipoArchivo = mime_content_type($archivo);
    if ($tipoArchivo !== 'image/png') {
        die("Error: El archivo no es una imagen PNG.");
    }

    $contenido = file_get_contents($archivo);

    if ($tabla === "locales") {
        $query = "UPDATE locales SET imagen = ? WHERE id = ?";
    } elseif ($tabla === "novedades") {
        $query = "UPDATE novedades SET imagen = ? WHERE id = ?";
    } else {
        die("Error: Tabla no válida.");
    }

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $contenido, $id);
    $stmt->send_long_data(0, $contenido);  

    if ($stmt->execute()) {
        echo "Imagen subida correctamente.";
    } else {
        echo "Error al subir la imagen: " . $stmt->error;
    }

    $stmt->close();
}


?>
