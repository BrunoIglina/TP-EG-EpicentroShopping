<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ids = $_POST['id_local'];
    $nombres = $_POST['nombre_local'];
    $ubicaciones = $_POST['ubicacion_local'];
    $rubros = $_POST['rubro_local'];
    $idUsuarios = $_POST['idUsuario'];

    // Actualiza cada local en la base de datos
    foreach ($ids as $index => $id_local){
        $nombre = $nombres[$index];
        $ubicacion = $ubicaciones[$index];
        $rubro = $rubros[$index];
        $idUsuario = $idUsuarios[$index];
        include "get_dueño.php";
        if(!($result_dueño -> num_rows > 0)){
            echo "El usuario ingresado para el local: ", $id_local, " no es dueño.";
        }else{
            $query = "UPDATE locales SET nombre = '$nombre', ubicacion = '$ubicacion', rubro = '$rubro', idUsuario = '$idUsuario' WHERE id = '$id_local'";
            if(!($conn->query($query))){
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    echo "Locales actualizados con éxito";
    header("Location: ../public/admin_locales.php");
    exit();
}
?>