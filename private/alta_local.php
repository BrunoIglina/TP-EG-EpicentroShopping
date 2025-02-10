<?php
include '../env/shopping_db.php';
include 'functions_usuarios.php';
include 'functions_locales.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nombre_local = $_POST['nombre_local'];
    $ubicacion_local = $_POST['ubicacion_local'];
    $rubro_local = $_POST['rubro_local'];
    $id_dueño = $_POST['id_dueño'];

    $dueño = get_dueño($id_dueño);

    if ($dueño){

        $local = get_local_by_nombre($nombre_local);
        
        if(!($local)){

            $id_dueño = $dueño['id'];

            $qry_alta = "INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES ('$nombre_local','$ubicacion_local','$rubro_local','$id_dueño')";

            if ($conn->query($qry_alta) === TRUE) {
                echo "Local dado de alta con éxito";
                header("Location: ../public/admin_locales.php");
                exit();

            }else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

        }else{

            echo "Ya existe un local con éste nombre: ", $nombre_local;

        }

    }else{

        echo "El usuario ingresado no es un dueño";

    }
    
}
$conn->close();

?>
