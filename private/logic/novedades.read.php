<?php
require_once __DIR__ . '/queries/novedades.queries.php';

switch ($vista) {

    case 'admin_novedades':
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $novedades = get_all_novedades($limit, $offset) ?? [];
        $total_novedades = get_total_novedades();
        $total_pages = max(1, (int)ceil($total_novedades / $limit));
        break;

    case 'admin_novedad_agregar':
        $categorias = get_categorias() ?? [];
        break;

    case 'admin_novedad_editar':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?vista=admin_novedades');
            exit();
        }
        $novedad = get_novedad($id);
        if (!$novedad) {
            header('Location: index.php?vista=admin_novedades');
            exit();
        }
        $categorias = get_categorias() ?? [];
        break;

    case 'novedades':
        $limit = 5;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $id_usuario = $_SESSION['user_id'] ?? 0;
        $tipo_usuario = $_SESSION['tipo'] ?? 'Cliente';
        $categoria_usuario = $_SESSION['categoria'] ?? 'Inicial';
        $todas_novedades = get_novedades_permitidas($id_usuario, $tipo_usuario, $categoria_usuario) ?? [];
        $total_novedades = count($todas_novedades);
        $total_pages = max(1, (int)ceil($total_novedades / $limit));
        $novedades = array_slice($todas_novedades, $offset, $limit);
        break;
}
