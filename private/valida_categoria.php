<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes estar registrado para validar la categoría.";
    exit();
}

$promo_id = $_GET['promo_id'];

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');




$sql = "SELECT idCliente FROM promociones_cliente WHERE idPromocion = $promo_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $cliente_id = $row['idCliente'];

    $sql = "SELECT COUNT(*) AS total_aceptadas FROM promociones_cliente WHERE idCliente = $cliente_id AND estado = 'aceptada'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_aceptadas = $row['total_aceptadas'];

    $sql = "SELECT categoria FROM usuarios WHERE id = $cliente_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
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
            echo "Categoría del cliente actualizada a $nueva_categoria.";
        } else {
            echo "Error al actualizar la categoría del cliente: " . $conn->error;
        }
    } else {
        echo "La categoría del cliente no ha cambiado.";
    }
} else {
    echo "No se encontró la promoción o el cliente asociado.";
}

$conn->close();
?>