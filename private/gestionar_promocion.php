<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "Debes estar registrado para gestionar las promociones.";
        exit();
    }

    $usuario_id = $_SESSION['user_id'];
    $promo_id = $_POST['promo_id'];
    $accion = $_POST['accion'];

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');


    if ($accion == 'aceptar') {
        $sql = "UPDATE promociones_cliente SET estado = 'aceptada' WHERE idPromocion = $promo_id";
    } else if ($accion == 'rechazar') {
        $sql = "UPDATE promociones_cliente SET estado = 'rechazada' WHERE idPromocion = $promo_id";
    }

    if ($conn->query($sql) === TRUE) {
        if ($accion == 'aceptar') {
            header("Location: valida_categoria.php?promo_id=$promo_id");
            exit();
        } else {
            $mensaje = "Promoción rechazada exitosamente.";
            echo "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <link rel='stylesheet' href='../css/styles.css'>
                <title>Confirmación</title>
            </head>
            <body>
                <script>
                    alert('$mensaje');
                    window.location.href = '../gestion_promos.php';
                </script>
            </body>
            </html>";
        }
    } else {
        echo "Error al gestionar la promoción: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
