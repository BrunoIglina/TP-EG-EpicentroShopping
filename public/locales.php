<?php
require_once __DIR__ . '/../private/logic/functions/functions_locales.php';
require_once __DIR__ . '/../private/config/rubros.php';
require_once __DIR__ . '/../includes/navigation_history.php';
require_once __DIR__ . '/../includes/security_headers.php';

$locales = get_all_locales();

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

// FUNCIÓN AUXILIAR PARA URLs SEGURAS
// Esta función evita perder los filtros
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
  <link rel="icon" type="image/png" href="./assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/header.css">
  <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="./css/footer.css">
  <link rel="stylesheet" href="./css/back_button.css">
  <link rel="stylesheet" href="./css/tarjetas.css">
  <link rel="stylesheet" href="./css/fix_header.css">

  <title>Epicentro Shopping - Locales</title>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . './../includes/header.php'; ?>

    <main>
      <?php include __DIR__ . '/../includes/back_button.php'; ?>
      <div class="container-fluid">

        <form method="GET" action="index.php">
          <input type="hidden" name="vista" value="locales">

          <div class="row mb-4">
            <div class="col-12 d-flex">
              <input type="text" name="nombre_local" class="form-control me-2"
                placeholder="Buscar por nombre del local..."
                value="<?php echo isset($_GET['nombre_local']) ? htmlspecialchars($_GET['nombre_local']) : ''; ?>">
              <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3" style="padding: 0.5rem;">
              <h3>Filtrar por:</h3>

              <div class="form-group mb-2">
                <select name="rubro" class="form-control">
                  <option value="">Todos los rubros</option>
                  <?php foreach ($rubros as $label => $value) { ?>
                  <option value="<?php echo htmlspecialchars($value); ?>"
                    <?php echo (isset($_GET['rubro']) && $_GET['rubro'] == $value) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                  </option>
                  <?php } ?>
                </select>
              </div>


              <button type="submit" class="btn btn-secondary w-100">Aplicar Filtros</button>

              <a href="index.php?vista=locales" class="btn btn-outline-danger w-100 mt-2">Limpiar Filtros</a>
            </div>

            <div class="col-md-9">

              <div class="row">
                <?php
                // (Empty State)
                if (empty($paginated_locales)) { ?>
                <div class="col-12 text-center py-5">
                  <h4 class="text-muted">No se encontraron locales con los filtros aplicados.</h4>
                </div>
                <?php } else {
                  foreach ($paginated_locales as $local) { ?>

                <div class="col-md-4 col-sm-12" style="padding: .5rem;">

                  <div class="card h-100 shadow-sm text-center">

                    <div style="height: 200px; overflow: hidden;">
                      <img src="index.php?vista=imagen&local_id=<?php echo $local['id']; ?>" class="card-img-top"
                        alt="<?php echo htmlspecialchars($local['nombre']); ?>"
                        style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div class="card-body d-flex flex-column">
                      <h4 class="card-title"><?php echo htmlspecialchars($local['nombre']); ?></h4>

                      <p class="card-text mb-2">
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($local['rubro']); ?></span>
                      </p>

                      <p class="card-text mt-auto text-muted" style="font-size: 0.9rem;">
                        <small>Ubicación: <?php echo htmlspecialchars($local['ubicacion']); ?></small>
                      </p>

                      <a href="index.php?vista=promociones&local_id=<?php echo $local['id']; ?>"
                        class="btn btn-primary mt-3 w-100">Ver Promociones</a>
                    </div>
                  </div>
                </div>
                <?php } ?>
                <?php } ?>
              </div>

              <?php
              if ($total_pages > 1) { ?>
              <nav class="d-flex justify-content-center mt-4">
                <ul class="pagination">

                  <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo getQueryString($current_page - 1, $_GET); ?>">Anterior</a>
                  </li>

                  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                  <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo getQueryString($i, $_GET); ?>"><?php echo $i; ?></a>
                  </li>
                  <?php } ?>

                  <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo getQueryString($current_page + 1, $_GET); ?>">Siguiente</a>
                  </li>
                </ul>
              </nav>
              <?php } ?>

            </div>
          </div>
        </form>

      </div>
    </main>
    <?php include __DIR__ . './../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>