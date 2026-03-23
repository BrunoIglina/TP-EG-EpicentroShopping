<?php
require_once __DIR__ . '/queries/usuarios.queries.php';

switch ($vista) {

    case 'admin_aprobar_clientes':
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $clientes = get_usuarios_pendientes('Cliente', $limit, $offset) ?? [];
        $total_clientes = get_total_usuarios_pendientes('Cliente');
        $total_pages = max(1, (int)ceil($total_clientes / $limit));
        break;

    case 'admin_aprobar_duenos':
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $duenos = get_usuarios_pendientes('Dueno', $limit, $offset) ?? [];
        $total_duenos = get_total_usuarios_pendientes('Dueno');
        $total_pages = max(1, (int)ceil($total_duenos / $limit));
        break;

    case 'cliente_perfil':
        $user = get_usuario((int)$_SESSION['user_id']);
        if (!$user) {
            header('Location: index.php?vista=login');
            exit();
        }
        $total_promos_usadas = get_total_promociones_usadas_cliente((int)$_SESSION['user_id']);
        break;

    case 'cliente_mod_perfil':
        require_once __DIR__ . '/helpers/email.php';
        $user = get_usuario((int)$_SESSION['user_id']);
        if (!$user) {
            header('Location: index.php?vista=login');
            exit();
        }
        break;
}
