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

function get_novedades_permitidas($id_usuario, $tipo_usuario, $categoria_usuario) {
    include '../env/shopping_db.php';

    // Función para convertir el nombre del mes al español
    function mesEnEspañol($mesIngles) {
        $meses = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];
        return $meses[$mesIngles];
    }

    $today = date("Y-m-d");

    if ($tipo_usuario == 'Dueño' || $tipo_usuario == 'Administrador' || $categoria_usuario == 'Premium') {
        $qry_novedad = "SELECT * FROM novedades WHERE '$today' BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC";
    } else {
        $qry_novedad = ($categoria_usuario == 'Medium') ? 
            "SELECT * FROM novedades WHERE categoria != 'Premium' AND '$today' BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC" : 
            "SELECT * FROM novedades WHERE categoria = 'Inicial' AND '$today' BETWEEN fecha_desde AND fecha_hasta ORDER BY fecha_desde DESC";
    }

    if (!$result_novedad = $conn->query($qry_novedad)) {
        echo "Error: " . $qry_novedad . "<br>" . $conn->error;
    } else {
        $novedades = $result_novedad->fetch_all(MYSQLI_ASSOC);
        foreach ($novedades as &$novedad) {        
            // Fecha en formato Y-m-d
            $fecha_original = $novedad['fecha_desde'];
    
            // Crear un objeto DateTime a partir de la fecha original
            $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_original);
            
            $fecha_formateada = $fecha_obj->format('d') . " de " . mesEnEspañol($fecha_obj->format('F')) . " de " . $fecha_obj->format('Y');
            $novedad['fecha_desde'] = "Fecha: " . $fecha_formateada;
        }
        return $novedades;
    }
}
?>
