<?php
require_once __DIR__ . '/queries/promociones.queries.php';

function handle_cliente_actions()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        $_SESSION['mensaje_error'] = "Debes iniciar sesión.";
        header("Location: index.php?vista=login");
        exit();
    }

    switch ($action) {
        case 'pedir_promocion':
            $promo_id = filter_input(INPUT_POST, 'promo_id', FILTER_VALIDATE_INT);
            if (!$promo_id) {
                $_SESSION['mensaje_error'] = "ID de promoción inválido.";
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?vista=locales'));
                exit();
            }

            $result = pedir_promocion($user_id, $promo_id);
            if ($result['success']) {
                $_SESSION['mensaje_exito'] = $result['message'];
            } else {
                $_SESSION['mensaje_error'] = $result['message'];
            }
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?vista=locales'));
            exit();

        default:

            break;
    }
}

handle_cliente_actions();
