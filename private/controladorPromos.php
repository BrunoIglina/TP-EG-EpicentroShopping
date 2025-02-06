<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Formulario recibido.<br>";  

    $local_id = $_POST['local_id']; 
    $textoPromo = $_POST['textoPromo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $diasSemana = $_POST['diasSemana'];
    $categoriaCliente = $_POST['categoriaCliente'];
    $estadoPromo = 'Pendiente';  

    echo "Datos recibidos: $local_id, $textoPromo, $fecha_inicio, $fecha_fin, $diasSemana, $categoriaCliente, $estadoPromo.<br>";  // Mensaje de depuración

    $conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    echo "Conexión a la base de datos exitosa.<br>";  

    
    $hoy = date('Y-m-d');
    if ($fecha_inicio < $hoy) {
        $_SESSION['error'] = "La fecha de inicio no puede ser anterior a hoy.";
        header("Location: ../public/darAltaPromos.php");
        exit();
    } else if ($fecha_fin < $fecha_inicio) {
        $_SESSION['error'] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
        header("Location: ../public/darAltaPromos.php");
        exit();
    }

    
    $sql = "INSERT INTO promociones (local_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, estadoPromo)
            VALUES ('$local_id', '$textoPromo', '$fecha_inicio', '$fecha_fin', '$diasSemana', '$categoriaCliente', '$estadoPromo')";

    echo "Consulta SQL: $sql<br>";  

    if ($conn->query($sql) === TRUE) {
        echo "Promoción agregada exitosamente.<br>";
    } else {
        echo "Error en la consulta: " . $conn->error . "<br>";
    }

    $conn->close();
} else {
    echo "Método de solicitud no válido.<br>";
}
?>
