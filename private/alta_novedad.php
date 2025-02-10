<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../env/shopping_db.php';
include 'functions_novedades.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo_novedad = $_POST['titulo_novedad'];
    $texto_novedad = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];

    $today = date("Y-m-d");

    if ($fecha_hasta < $today) {
        echo "La fecha hasta ingresada ya caducó";
        exit();

    } else {
        // Usar sentencias preparadas para evitar errores de sintaxis y mejorar la seguridad
        $qry_alta = $conn->prepare("INSERT INTO novedades (tituloNovedad, textoNovedad, fecha_desde, fecha_hasta, categoria) VALUES (?, ?, ?, ?, ?)");
        $qry_alta->bind_param('sssss', $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria);

        if ($qry_alta->execute()) {
            $conn->close();
            echo "Novedad dada de alta con éxito";
            header("Location: ../public/admin_novedades.php");
            exit();

        } else {
            echo "Error: " . $qry_alta->error;
        }
    }
}
?>
