<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');


include 'functions_novedades.php';
include '../private/subirImagen.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo_novedad = $_POST['titulo_novedad'];
    $texto_novedad = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];

    $query_check = "SELECT id FROM novedades WHERE tituloNovedad = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $titulo_novedad);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensaje_error1'] = "Error: Ya existe una novedad con ese nombre.";
        header("Location: ../agregar_novedad.php");
        exit();
    }
    
    $stmt_check->close();

    $today = date("Y-m-d");

    if ($fecha_desde < $today) {
        $_SESSION['error'] = "La fecha desde no puede ser anterior a hoy.";
        header("Location: ../agregar_novedad.php");
        exit();
    }

    if ($fecha_hasta < $today) {
        $_SESSION['error'] = "La fecha hasta ingresada ya caducó.";
        header("Location: ../agregar_novedad.php"); 
        exit();
    } else {
        
        $query = "INSERT INTO novedades (tituloNovedad, textoNovedad, fecha_desde, fecha_hasta, categoria) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            $_SESSION['error'] = "Error al preparar la consulta: " . $conn->error;
            header("Location: ../agregar_novedad.php");
            exit();
        }

        $stmt->bind_param("sssss", $titulo_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $categoria);


        if ($stmt->execute()) {
            $id_novedad = $stmt->insert_id;
            $stmt->close();

            
            if (isset($_FILES['imagen_novedad']) && $_FILES['imagen_novedad']['error'] == 0) {
                $imagen_tmp = $_FILES['imagen_novedad']['tmp_name'];
                if (!subirImagen($id_novedad, $imagen_tmp, "novedades", $conn)) {
                    $delete_stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
                    $delete_stmt->bind_param("i", $id_novedad);
                    if ($delete_stmt->execute()) {
                        echo "Error: No se pudo subir la imagen. Puede que el archivo sea demasiado grande. La novedad ha sido eliminada.";
                    } else {
                        echo "Error: No se pudo subir la imagen y no se logró eliminar la novedad. ";
                    }
                    $delete_stmt->close();
                    $_SESSION['mensaje_error1'] = "No se pudo subir la imagen. Puede que el archivo sea demasiado grande.";
                    header("Location: ../agregar_novedad.php");
                    exit();
                }
            }
    
            
            $_SESSION['success'] = "Novedad dada de alta con éxito.";
            header("Location: ../admin_novedades.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $query->error;
            header("Location: ../agregar_novedad.php");
            exit();
        }
    }
}

?>