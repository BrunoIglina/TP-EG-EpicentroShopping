<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes estar registrado para validar la categoría.";
    exit();
}

$cliente_id = $_GET['cliente_id'];

$sql = "SELECT usu.categoria, COUNT(pxc.idCliente) AS total_aceptadas FROM promociones_cliente pxc
    INNER JOIN usuarios usu ON pxc.idCliente = usu.id
    WHERE pxc.idCliente = $cliente_id AND estado = 'aceptada'
    GROUP BY usu.categoria";

// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/../env/shopping_db.php');

if($result = $conn->query($sql)) {
    $row = $result->fetch_assoc();
    $total_aceptadas = $row['total_aceptadas'];
    $categoria_actual = $row['categoria'];


    $nueva_categoria = $categoria_actual;
    if ($categoria_actual == 'Inicial' && $total_aceptadas >= 3) {
        $nueva_categoria = 'Medium';
    } else if ($categoria_actual == 'Medium' && $total_aceptadas >= 5) {
        $nueva_categoria = 'Premium';
    }


    if ($nueva_categoria != $categoria_actual) {
        $sql = "UPDATE usuarios SET categoria = '$nueva_categoria' WHERE id = $cliente_id";
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            echo "Categoría del cliente actualizada a $nueva_categoria.";
            header("Location: ../gestion_promos.php");

        } else {
            echo "Error al actualizar la categoría del cliente: " . $conn->error;
            $conn->close();
        }
    } else {
        echo "La categoría del cliente no ha cambiado.";
        header("Location: ../gestion_promos.php");
    }
} else {
    echo "Error en la consulta: " . $conn->error;
    exit();
}

?>