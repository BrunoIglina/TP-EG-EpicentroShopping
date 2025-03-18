<?php
session_start();
require_once('../env/shopping_db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errores = [];

    $local_id = isset($_POST['local_id']) ? intval($_POST['local_id']) : null;
    $textoPromo = isset($_POST['textoPromo']) ? trim($_POST['textoPromo']) : null;
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
    $categoriaCliente = isset($_POST['categoriaCliente']) ? $_POST['categoriaCliente'] : null;
    $estadoPromo = 'Pendiente';

    if (!$local_id) {
        $errores[] = "Debe seleccionar un local.";
    }

    if (!$textoPromo) {
        $errores[] = "Debe ingresar un texto de promoción.";
    }

    $hoy = date('Y-m-d');
    if (!$fecha_inicio || !$fecha_fin) {
        $errores[] = "Debe ingresar ambas fechas.";
    } elseif ($fecha_inicio < $hoy) {
        $errores[] = "La fecha de inicio no puede ser anterior a hoy.";
    } elseif ($fecha_fin < $fecha_inicio) {
        $errores[] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
    }

    $categoriasValidas = ["Inicial", "Medium", "Premium"];
    if (!$categoriaCliente || !in_array($categoriaCliente, $categoriasValidas)) {
        $errores[] = "Debe seleccionar una categoría válida.";
    }

    if (!isset($_POST['diasSemana']) || empty($_POST['diasSemana'])) {
        $errores[] = "Debe seleccionar al menos un día de la semana.";
        $diasSemanaStr = "";
    } else {
        $diasSemanaStr = implode(",", $_POST['diasSemana']);
    }

    if (!empty($errores)) {
        $_SESSION['error'] = implode("<br>", $errores);
        header("Location: ../darAltaPromos.php");
        exit();
    }

    $sql = "INSERT INTO promociones (local_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, estadoPromo)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $local_id, $textoPromo, $fecha_inicio, $fecha_fin, $diasSemanaStr, $categoriaCliente, $estadoPromo);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Promoción agregada exitosamente. El Administrador debe evaluarla.";
        header("Location: ../misPromos.php");
    } else {
        $_SESSION['error'] = "Error al agregar la promoción: " . $conn->error;
        header("Location: ../darAltaPromos.php");
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Método de solicitud no válido.";
    header("Location: ../darAltaPromos.php");
}
?>
