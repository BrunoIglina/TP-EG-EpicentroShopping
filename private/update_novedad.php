<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['id_novedad'];
    $titulos = $_POST['titulo_novedad'];
    $textos = $_POST['texto_novedad'];
    $fechas_desde = $_POST['fecha_desde'];
    $fechas_hasta = $_POST['fecha_hasta'];
    $categorias = $_POST['categoria'];

    $today = date("Y-m-d");

    foreach($ids as $index => $id_novedad){
        $titulo_novedad = $titulos[$index];
        $texto_novedad = $textos[$index];
        $fecha_desde = $fechas_desde[$index];
        $fecha_hasta = $fechas_hasta[$index];
        $categoria = $categorias[$index];

        $qry = "UPDATE novedades SET tituloNovedad = ?, textoNovedad = ?, fecha_desde = ?, fecha_hasta = ?, categoria = ? WHERE id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param('sssssi', $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria, $id_novedad);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Novedad actualizada con éxito.";
        } else {
            $_SESSION['error'] = "Error al actualizar la novedad con ID $id_novedad.";
        }
    }

    $conn->close();
    header("Location: ../public/admin_novedades.php");
    exit();
}
?>
