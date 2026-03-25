<?php
$filtered_locales = $locales;

// FILTRO: Por Nombre
if (isset($_GET['nombre_local']) && $_GET['nombre_local'] != '') {
  $nombre_local = $_GET['nombre_local'];
  $filtered_locales = array_filter($filtered_locales, function ($local) use ($nombre_local) {
    return stripos($local['nombre'], $nombre_local) !== false;
  });
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
  $local_id = $_GET['local_id'];
  $filtered_locales = array_filter($filtered_locales, function ($local) use ($local_id) {
    return $local['id'] == $local_id;
  });
}

// FILTRO: Por Rubro (Categoría)
if (isset($_GET['rubro']) && $_GET['rubro'] != '') {
  $rubro = $_GET['rubro'];
  $filtered_locales = array_filter($filtered_locales, function ($local) use ($rubro) {
    return $local['rubro'] == $rubro;
  });
}

// PAGINACIÓN
$items_per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($filtered_locales);
$total_pages = ceil($total_items / $items_per_page);
$start_index = ($current_page - 1) * $items_per_page;
$paginated_locales = array_slice($filtered_locales, $start_index, $items_per_page);

function getQueryString($page, $current_get)
{
  $params = $current_get;
  $params['page'] = $page;
  return http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <title>Nuestros Locales | Epicentro Shopping - Explorar Tiendas</title>

  <link rel="icon" type="image/png" href="public/assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="public/css/header.css">
  <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="public/css/footer.css">
  <link rel="stylesheet" href="public/css/back_button.css">
  <link rel="stylesheet" href="public/css/tarjetas.css">
  <link rel="stylesheet" href="public/css/fix_header.css">
</head>

<body>
  <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
    Saltar al contenido principal
  </a>

  <div class="wrapper">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main id="main-content" class="container-fluid py-4">
      <div class="row align-items-center mb-5 mt-3">
        <div class="col-2 col-md-1 text-start">
          <?php include __DIR__ . '/../includes/back_button.php'; ?>
        </div>

        <div class="col-8 col-md-10">
          <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
            Locales
          </h1>
        </div>

        <div class="col-2 col-md-1"></div>
      </div>

      <form method="GET" action="index.php">
        <input type="hidden" name="vista" value="locales">

        <div class="row mb-4">
          <div class="col-12 d-flex flex-column flex-md-row gap-2">
            <label for="nombre_local" class="visually-hidden">Buscar por nombre del local</label>
            <input type="text" id="nombre_local" name="nombre_local" class="form-control"
              placeholder="Buscar por nombre del local..."
              value="<?= isset($_GET['nombre_local']) ? htmlspecialchars($_GET['nombre_local']) : ''; ?>">
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
        </div>

        <div class="row">
          <aside class="col-md-3" style="padding: 0.5rem;">
            <div class="card p-3 shadow-sm border-0 rounded-3">
              <h2 class="h5 fw-bold mb-3">Filtrar por:</h2>

              <div class="mb-3">
                <label for="rubro_select" class="form-label small fw-bold">Categoría o Rubro</label>
                <select id="rubro_select" name="rubro" class="form-select">
                  <option value="">Todos los rubros</option>
                  <?php foreach ($rubros as $label => $value): ?>
                    <option value="<?= htmlspecialchars($value); ?>"
                      <?= (isset($_GET['rubro']) && $_GET['rubro'] == $value) ? 'selected' : ''; ?>>
                      <?= htmlspecialchars($label); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <button type="submit" class="btn btn-secondary w-100 mb-2">Aplicar Filtros</button>
              <a href="index.php?vista=locales" class="btn btn-outline-danger w-100">Limpiar Filtros</a>
            </div>
          </aside>

          <section class="col-md-9">
            <div class="row g-4">
              <?php if (empty($paginated_locales)): ?>
                <div class="col-12 text-center py-5">
                  <p class="text-muted fs-4">No se encontraron locales con los filtros aplicados.</p>
                </div>
              <?php else: ?>
                <?php foreach ($paginated_locales as $local): ?>
                  <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                      <div style="height: 180px; overflow: hidden;">
                        <img src="index.php?vista=imagen&local_id=<?= $local['id']; ?>"
                          class="card-img-top w-100 h-100"
                          alt="Logotipo del local <?= htmlspecialchars($local['nombre']); ?>"
                          style="object-fit: cover;">
                      </div>

                      <div class="card-body d-flex flex-column text-center p-4">
                        <h3 class="h5 card-title fw-bold text-dark"><?= htmlspecialchars($local['nombre']); ?></h3>

                        <div class="my-2">
                          <span class="badge bg-primary text-uppercase"><?= htmlspecialchars($local['rubro']); ?></span>
                        </div>

                        <p class="card-text mt-auto text-muted small">
                          <i class="bi bi-geo-alt"></i> Ubicación: <?= htmlspecialchars($local['ubicacion']); ?>
                        </p>

                        <a href="index.php?vista=promociones&local_id=<?= $local['id']; ?>"
                          class="btn btn-primary mt-3 w-100 py-2 fw-bold"
                          aria-label="Ver promociones de <?= htmlspecialchars($local['nombre']); ?>">
                          Ver Promociones
                        </a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
              <nav aria-label="Navegación de páginas de locales" class="mt-5">
                <ul class="pagination justify-content-center">
                  <li class="page-item <?= $current_page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?= getQueryString($current_page - 1, $_GET); ?>" <?= $current_page <= 1 ? 'aria-disabled="true"' : ''; ?>>Anterior</a>
                  </li>
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                      <a class="page-link" href="?<?= getQueryString($i, $_GET); ?>" <?= $i == $current_page ? 'aria-current="page"' : ''; ?>><?= $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?= getQueryString($current_page + 1, $_GET); ?>" <?= $current_page >= $total_pages ? 'aria-disabled="true"' : ''; ?>>Siguiente</a>
                  </li>
                </ul>
              </nav>
            <?php endif; ?>
          </section>
        </div>
      </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>