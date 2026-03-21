<?php
require_once __DIR__ . '/queries/promociones.queries.php';
require_once __DIR__ . '/queries/locales.queries.php';

switch ($vista) {

    case 'admin_promociones':
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $promociones = get_promociones_pendientes($limit, $offset) ?? [];
        $total_promociones = get_total_promociones_pendientes();
        $total_pages = max(1, (int)ceil($total_promociones / $limit));
        break;

    case 'dueno_promociones':
        $limit = 5;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $id_usuario = (int)$_SESSION['user_id'];
        $promociones = get_promociones_dueno($id_usuario, $limit, $offset) ?? [];
        $total_promociones = get_total_promociones_dueno($id_usuario);
        $total_pages = max(1, (int)ceil($total_promociones / $limit));
        break;

    case 'dueno_solicitudes':
        $items_per_page = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $items_per_page;
        $id_usuario = (int)$_SESSION['user_id'];
        $solicitudes = get_solicitudes_dueno($id_usuario, $items_per_page, $offset) ?? [];
        $total_items = get_total_solicitudes_dueno($id_usuario);
        $total_pages = max(1, (int)ceil($total_items / $items_per_page));
        break;

    case 'dueno_reportes':
        $id_usuario = (int)$_SESSION['user_id'];
        $filters = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin'    => $_GET['fecha_fin']    ?? '',
            'estadoPromo'  => $_GET['estadoPromo']  ?? '',
            'local_id'     => $_GET['local_id']     ?? '',
        ];
        $reportes = getReportesPromos($id_usuario, $filters) ?? [];
        $total = count($reportes);
        $locales_dueno = get_locales_por_dueno($id_usuario);
        break;

    case 'cliente_promociones':
        $limit = 4;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $id_usuario = (int)$_SESSION['user_id'];
        $promos = get_promociones_cliente($id_usuario, $limit, $offset) ?? [];
        $total_promos = get_total_promociones_cliente($id_usuario);
        $total_pages = max(1, (int)ceil($total_promos / $limit));
        break;
}
