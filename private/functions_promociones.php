<?php
function get_all_promociones_activas(){
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');
    
    $qry_promociones = "SELECT * FROM promociones WHERE estadoPromo = 'Aprobada' AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin ORDER BY fecha_fin DESC";

    if(!($result_promociones = $conn->query($qry_promociones))){
        echo "Error: " . $qry_promociones . "<br>" . $conn->error;
    }else{
        $promociones = $result_promociones->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $promociones;
    }

    $conn->close();
}
?>