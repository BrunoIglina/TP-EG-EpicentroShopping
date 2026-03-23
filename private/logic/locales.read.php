<?php
require_once __DIR__ . '/queries/locales.queries.php';

switch ($vista) {

    case 'admin_locales':
        $limit = 10;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $locales = get_all_locales($limit, $offset) ?? [];
        $total_locales = get_total_locales();
        $total_pages = max(1, (int)ceil($total_locales / $limit));
        break;

    case 'admin_local_agregar':
        require_once __DIR__ . '/queries/usuarios.queries.php';
        require_once __DIR__ . '/../config/rubros.php';
        $dueños = get_all_dueños() ?? [];
        break;

    case 'admin_local_editar':
        require_once __DIR__ . '/queries/usuarios.queries.php';
        require_once __DIR__ . '/../config/rubros.php';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?vista=admin_locales');
            exit();
        }
        $local = get_local($id);
        if (!$local) {
            header('Location: index.php?vista=admin_locales');
            exit();
        }
        $dueños = get_all_dueños() ?? [];
        break;

    case 'landing':
        $locales = get_locales_solicitados() ?? [];
        break;

    case 'locales':
        require_once __DIR__ . '/../config/rubros.php';
        $locales = get_all_locales() ?? [];
        break;

    case 'dueno_promocion_agregar':
        require_once __DIR__ . '/queries/locales.queries.php';
        $locales = get_locales_por_dueno((int)$_SESSION['user_id']) ?? [];
        break;

    case 'promociones':
        require_once __DIR__ . '/queries/usuarios.queries.php';
        require_once __DIR__ . '/../config/rubros.php';
        $local_id = isset($_GET['local_id']) ? (int)$_GET['local_id'] : 0;
        if (!$local_id) {
            header('Location: index.php?vista=locales');
            exit();
        }
        $local = get_local($local_id);
        if (!$local) {
            header('Location: index.php?vista=locales');
            exit();
        }
        require_once __DIR__ . '/queries/promociones.queries.php';
        $limit = 9;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $promos = get_promociones_by_local($local_id, $limit, $offset) ?? [];
        $total_promos = get_total_promociones_by_local($local_id);
        $total_pages = max(1, (int)ceil($total_promos / $limit));
        $categorias = get_categorias() ?? [];
        break;
}
