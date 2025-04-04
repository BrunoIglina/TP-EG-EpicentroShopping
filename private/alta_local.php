<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: ../public/index.php");
    exit();
}

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');


include '../private/subirImagen.php';
include '../private/functions_usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_local = $_POST['nombre_local'];
    $ubicacion_local = $_POST['ubicacion_local'];
    $rubro_local = $_POST['rubro_local'];
    $email_dueño = $_POST['email_dueño']; 


    $query_check = "SELECT id FROM locales WHERE nombre = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $nombre_local);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensaje_error1'] = "Error: Ya existe un local con ese nombre.";
        header("Location: ../agregar_local.php");
        exit();
    }
    
    $stmt_check->close();


    $dueño = get_dueño_by_email($email_dueño);
    
    if (!$dueño) {
        echo "Error: No se encontró un usuario con ese email.";
        exit();
    }

    $idUsuario = $dueño['id'];

    if (empty($nombre_local) || empty($ubicacion_local) || empty($rubro_local) || empty($idUsuario)) {
        echo "Error: Todos los campos son obligatorios.";
        exit();
    }

    $query = "INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);


    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit();
    }


    $stmt->bind_param("sssi", $nombre_local, $ubicacion_local, $rubro_local, $idUsuario);


    if ($stmt->execute()) {
        $id_local = $stmt->insert_id;
        $stmt->close();

        
        
        if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0) {
            $imagen_tmp = $_FILES['imagen_local']['tmp_name'];
            if (!subirImagen($id_local, $imagen_tmp, "locales", $conn)) {
                $delete_stmt = $conn->prepare("DELETE FROM locales WHERE id = ?");
                $delete_stmt->bind_param("i", $id_local);
                if ($delete_stmt->execute()) {
                    echo "Error: No se pudo subir la imagen. Puede que el archivo sea demasiado grande. El local ha sido eliminado.";
                } else {
                    echo "Error: No se pudo subir la imagen y no se logró eliminar el local. Contacte al administrador.";
                }
                $delete_stmt->close();
                $_SESSION['mensaje_error1'] = "No se pudo subir la imagen. Puede que el archivo sea demasiado grande.";
                header("Location: ../agregar_local.php");
                exit();
            }
        }


        header("Location: ../admin_locales.php?success=1");
        exit();
    } else {
        echo "Error al registrar el local: " . $stmt->error;
        $stmt->close();
        $_SESSION['mensaje_error1'] = "Error al registrar el local: " . $stmt->error;
        header("Location: ../agregar_local.php");
        exit();
    }
}
?>
