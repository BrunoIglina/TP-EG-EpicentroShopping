<?php

function get_all_novedades(){
    include '../env/shopping_db.php';
    $qry_novedad = "SELECT * FROM novedades";
    if(!($result_novedad = $conn->query($qry_novedad))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $novedades = $result_novedad -> fetch_all(MYSQLI_ASSOC);
        return $novedades;
    };
}

function get_novedad($id){
    include '../env/shopping_db.php';
    $qry_novedad = "SELECT * FROM novedades WHERE id = '$id'";
    if(!($result_novedad = $conn->query($qry_novedad))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $novedad = $result_novedad -> fetch_assoc();
        return $novedad;
    };
}

function get_categorias() {
    return ['Inicial', 'Medium', 'Premium'];
}

?>