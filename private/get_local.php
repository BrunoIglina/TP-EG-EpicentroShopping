<?php

include '../env/shopping_db.php';

$qry_locales = "SELECT * FROM locales WHERE id = '$id_local'";
if(!($result_locales = $conn->query($qry_locales))){
    echo "Error: " . $sql . "<br>" . $conn->error;
};

?>