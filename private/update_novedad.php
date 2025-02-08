<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids= $_POST['id_novedad'];
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
        
        if($fecha_hasta < $today){
            echo "La fecha hasta ingresada ya caducó";

        }else{
            $qry = "UPDATE novedades SET tituloNovedad = '$titulo_novedad',textoNovedad = '$texto_novedad', fecha_desde = '$fecha_desde', fecha_hasta = '$fecha_hasta', categoria = '$categoria' WHERE id = $id_novedad";

            if ($conn->query($qry) === TRUE) {
                echo "Novedad actualizada con éxito";
                
            }else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    header("Location: ../public/admin_novedades.php");
    $conn->close();
    exit();
}

?>