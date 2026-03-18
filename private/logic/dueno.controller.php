<?php

function gestionar_solicitud_cliente_dueno()
{
    require_once __DIR__ . '/../config/database.php';
    $conn = getDB();

    $promo_id = filter_input(INPUT_POST, 'promo_id', FILTER_VALIDATE_INT);
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $accion = $_POST['accion'] ?? '';

    if (!$promo_id || !$cliente_id || !in_array($accion, ['aceptar', 'rechazar'])) {
        $_SESSION['error'] = "Datos inválidos.";
        header("Location: index.php?vista=dueno_gestion_promociones");
        exit();
    }

    $estado = ($accion == 'aceptar') ? 'aceptada' : 'rechazada';

    $stmt = $conn->prepare("UPDATE promociones_cliente SET estado = ? WHERE idPromocion = ? AND idCliente = ?");
    $stmt->bind_param("sii", $estado, $promo_id, $cliente_id);

    if ($stmt->execute()) {
        $stmt->close();

        if ($accion == 'aceptar') {
            validar_categoria_dueno($cliente_id);
        } else {
            $_SESSION['success'] = "Promoción rechazada exitosamente.";
            header("Location: index.php?vista=dueno_gestion_promociones");
            exit();
        }
    } else {
        error_log("Error al gestionar solicitud: " . $stmt->error);
        $_SESSION['error'] = "Error al gestionar la promoción.";
        header("Location: index.php?vista=dueno_gestion_promociones");
        exit();
    }
}

function validar_categoria_dueno($cliente_id)
{
    require_once __DIR__ . '/../config/database.php';
    $conn = getDB();

    if (!$cliente_id) {
        $_SESSION['error'] = "ID de cliente inválido";
        header("Location: index.php?vista=dueno_gestion_promociones");
        exit();
    }

    $stmt = $conn->prepare("SELECT usu.categoria, COUNT(pxc.idCliente) AS total_aceptadas 
                                                        FROM promociones_cliente pxc
                                                        INNER JOIN usuarios usu ON pxc.idCliente = usu.id
                                                        WHERE pxc.idCliente = ? AND estado = 'aceptada'
                                                        GROUP BY usu.categoria");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $_SESSION['success'] = "Promoción aceptada.";
        header("Location: index.php?vista=dueno_gestion_promociones");
        exit();
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    $total_aceptadas = $row['total_aceptadas'];
    $categoria_actual = $row['categoria'];

    $nueva_categoria = $categoria_actual;
    if ($categoria_actual == 'Inicial' && $total_aceptadas >= 3) {
        $nueva_categoria = 'Medium';
    } else if ($categoria_actual == 'Medium' && $total_aceptadas >= 5) {
        $nueva_categoria = 'Premium';
    }

    if ($nueva_categoria != $categoria_actual) {
        $stmt_update = $conn->prepare("UPDATE usuarios SET categoria = ? WHERE id = ?");
        $stmt_update->bind_param("si", $nueva_categoria, $cliente_id);

        if ($stmt_update->execute()) {
            $_SESSION['success'] = "Promoción aceptada. Categoría del cliente actualizada a $nueva_categoria.";
        } else {
            error_log("Error al actualizar categoría: " . $stmt_update->error);
            $_SESSION['error'] = "Error al actualizar la categoría del cliente.";
        }
        $stmt_update->close();
    } else {
        $_SESSION['success'] = "Promoción aceptada. La categoría del cliente no ha cambiado.";
    }

    header("Location: index.php?vista=dueno_gestion_promociones");
    exit();
}


function handle_dueno_actions()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
        $_SESSION['error'] = "Acceso no autorizado.";
        header("Location: index.php");
        exit();
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'gestionar_solicitud':
            gestionar_solicitud_cliente_dueno();
            break;
        // Otros casos para Dueño
        default:
            $_SESSION['error'] = "Acción no válida.";
            header("Location: index.php");
            exit();
    }
}

handle_dueno_actions();
