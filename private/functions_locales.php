<?php

function get_all_locales(){
    include "../env/shopping_db.php";
    $qry_locales = "SELECT * FROM locales ORDER BY nombre";
    if(!($result_locales = $conn->query($qry_locales))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $locales = $result_locales -> fetch_all(MYSQLI_ASSOC);
        return $locales;
    };
}

function get_local($id_local){
    include "../env/shopping_db.php";
    $qry_local = "SELECT * FROM locales WHERE id = '$id_local'";
    if(!($result_local = $conn->query($qry_local))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $local = $result_local -> fetch_assoc();
        return $local;
    };
}

function get_local_by_nombre($nombre){
    include "../env/shopping_db.php";
    $qry_local = "SELECT * FROM locales WHERE nombre = '$nombre'";
    if(!($result_local = $conn->query($qry_local))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $local = $result_local -> fetch_assoc();
        return $local;
    };
}

?>