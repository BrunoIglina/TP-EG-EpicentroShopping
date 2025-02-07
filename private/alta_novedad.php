<?php
include '../env/shopping_db.php';
include 'novedades_functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $texto_novedad = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];

    $today = date("Y-m-d");

    if($fecha_hasta < $today){
        echo "La fecha hasta ingresada ya caducó";
        exit();

    }else{
        $qry_alta = "INSERT INTO novedades (textoNovedad, fecha_desde, fecha_hasta, categoria) VALUES ('$texto_novedad','$fecha_desde','$fecha_hasta','$categoria')";

        if ($conn->query($qry_alta) === TRUE) {
            $conn->close();
            echo "Novedad dada de alta con éxito";
            header("Location: ../public/admin_novedades.php");
            exit();

        }else{
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>