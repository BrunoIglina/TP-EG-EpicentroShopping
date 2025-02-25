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

    if ($fecha_desde < $today) {
        $_SESSION['error'] = "La fecha desde no puede ser anterior a hoy.";
        header("Location: agregar_novedad.php");
        exit();
    }

    if ($fecha_hasta < $today) {
        $_SESSION['error'] = "La fecha hasta ingresada ya caducó.";
        header("Location: agregar_novedad.php"); 
        exit();
    } else {
        
        $qry_alta = $conn->prepare("INSERT INTO novedades (tituloNovedad, textoNovedad, fecha_desde, fecha_hasta, categoria) VALUES (?, ?, ?, ?, ?)");
        $qry_alta->bind_param('sssss', $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria);

        if ($qry_alta->execute()) {
            $_SESSION['success'] = "Novedad dada de alta con éxito.";
            header("Location: ../public/admin_novedades.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $qry_alta->error;
            header("Location: agregar_novedad.php");
            exit();
        }
    }
}

?>