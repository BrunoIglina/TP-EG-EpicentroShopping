<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";
include "functions_usuarios.php";
include "functions_locales.php";
include '../private/subirImagen.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_local = $_POST['id_local'];
    $nombre_antiguo = $_POST['nombre_antiguo_local'];
    $nombre = $_POST['nombre_local'];
    $ubicacion = $_POST['ubicacion_local'];
    $rubro = $_POST['rubro_local'];
    $id_dueño = $_POST['id_dueño'];
   
    if (empty($id_local) || empty($nombre) || empty($ubicacion) || empty($rubro) || empty($id_dueño)) {
        die("Error: Todos los campos son obligatorios.");
    }
    

    $dueño = get_dueño($id_dueño);

    if (!($dueño)) {

        echo "El usuario ingresado para el local: " . htmlspecialchars($nombre_local, ENT_QUOTES, 'UTF-8') . " no es dueño.";
        exit();

    } else {

        $local = get_local_by_nombre($nombre_local);

        if (($local) && $nombre_antiguo != $nombre_local) {

            echo "Ya existe un local con éste nombre: " . htmlspecialchars($nombre_local, ENT_QUOTES, 'UTF-8');
            exit();

        } else {

            $id_dueño = $dueño['id'];

            // Usar sentencias preparadas para evitar errores de sintaxis y mejorar la seguridad
            $stmt = $conn->prepare("UPDATE locales SET nombre = ?, ubicacion = ?, rubro = ?, idUsuario = ? WHERE id = ?");
            $stmt->bind_param('ssssi', $nombre, $ubicacion, $rubro, $id_dueño, $id_local);

            if ($stmt->execute()) {
                $stmt->close();
        
                if (isset($_FILES['imagen_local']) && $_FILES['imagen_local']['error'] == 0) {
                    $imagen_tmp = $_FILES['imagen_local']['tmp_name'];
                    subirImagen($id_local, $imagen_tmp, "locales", $conn);
                } else {
                    echo "No se modificó la imagen.";
                }
                
        
        
                header("Location: ../public/admin_locales.php?success=1");
                exit();
            } else {
                echo "Error al registrar el local: " . $stmt->error;
                $stmt->close();
                exit();
            }


        }

    }
    echo "Locales actualizados con éxito";
    header("Location: ../public/admin_locales.php");
    exit();
}
?>
