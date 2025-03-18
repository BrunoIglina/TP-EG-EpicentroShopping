<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";
include "../private/subirImagen.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_novedad = $_POST['id_novedad'];
    $titulo_novedad = $_POST['titulo_novedad'];
    $texto_novedad = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];

    $today = date("Y-m-d");

    $qry = "UPDATE novedades SET tituloNovedad = ?, textoNovedad = ?, fecha_desde = ?, fecha_hasta = ?, categoria = ? WHERE id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param('sssssi', $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria, $id_novedad);
    
    if ($stmt->execute()) {
        $stmt->close();
        if (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0) {
            $imagen_tmp = $_FILES['imagen_novedad']['tmp_name'];
            subirImagen($id_novedad, $imagen_tmp, "novedades", $conn);
        }

    } else {
        $_SESSION['error'] = "Error al actualizar la novedad con ID $id_novedad.";
    }

    $conn->close();
    header("Location: ../public/admin_novedades.php");
    exit();
}
?>
