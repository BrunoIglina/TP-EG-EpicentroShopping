<?php
function subirImagen($id, $archivo, $tabla, $conn) {
    if (!isset($archivo) || empty($archivo)) {
        die("Error: No se ha cargado la imagen correctamente.");
    }

    $tipoArchivo = mime_content_type($archivo);
    $tiposPermitidos = ['image/png', 'image/jpeg']; 
    if (!in_array($tipoArchivo, $tiposPermitidos)) {
        die("Error: El archivo no es una imagen PNG o JPEG.");
    }

    $contenido = file_get_contents($archivo);

    if ($contenido === false) {
        die("Error: No se pudo leer el contenido del archivo.");
    }
    

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

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Imagen subida correctamente.";
    } else {
        echo "No se realizó ningún cambio en la imagen.";
    }
    

    $stmt->close();
}


?>
