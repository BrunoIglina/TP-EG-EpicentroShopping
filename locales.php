<?php

include './private/functions_locales.php';
include './private/rubros.php';

$locales = get_all_locales();
$filtered_locales = $locales;


if (isset($_GET['nombre_local']) && $_GET['nombre_local'] != '') {
    $nombre_local = $_GET['nombre_local'];
    $filtered_locales = array_filter($filtered_locales, function($local) use ($nombre_local) {
        return stripos($local['nombre'], $nombre_local) !== false;
    });
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = $_GET['local_id'];
    $filtered_locales = array_filter($filtered_locales, function($local) use ($local_id) {
        return $local['id'] == $local_id;
    });
}

if (isset($_GET['rubro']) && $_GET['rubro'] != '') {
    $rubro = $_GET['rubro'];
    $filtered_locales = array_filter($filtered_locales, function($local) use ($rubro) {
        return $local['rubro'] == $rubro;
    });
}


$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 


$total_locales = count($filtered_locales);

$filtered_locales = array_slice($filtered_locales, $offset, $limit);

$total_pages = ceil($total_locales / $limit);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/tarjetas.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Locales</title>
</head>
<body>
<div class="wrapper">
    <?php include './includes/header.php'; ?>
    <main>

        <div class="container-fluid">

            <div class="row" style="padding: 0.5rem">
                <form class="d-flex w-100" method="GET">
                    <div class="flex-grow-1 me-2">
                        <input type="text" name="nombre_local" class="form-control" placeholder="Buscar por nombre del local..." value="<?php echo isset($_GET['nombre_local']) ? $_GET['nombre_local'] : ''; ?>">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>


            <div class="row" style="padding: 0.5rem">
                <!-- Filtros -->
                <div class="col-md-3">
                    <form class="d-flex flex-column gap-2">

                        <div class="form-group">
                            <select name="rubro" class="form-control">
                                <option value="">Todos los rubros</option>
                                <?php foreach ($rubros as $label => $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php echo (isset($_GET['rubro']) && $_GET['rubro'] == $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="number" name="local_id" class="form-control" placeholder="Buscar por ID del local..." min="1" value="<?php echo isset($_GET['local_id']) ? $_GET['local_id'] : ''; ?>">
                        </div>

                        <button type="submit" class="btn btn-secondary align-self-start mt-2">Filtrar</button>

                    </form>
                </div>

                <div class="col-md-9">
                    <div class="row">

                        <?php if ($filtered_locales): ?>
                            <?php foreach ($filtered_locales as $local) { ?>
                                <div class="col-md-4 col-sm-12" style="padding: .5rem;">
                                    <a href="promociones.php?local_id=<?php echo $local['id']; ?>&local_nombre=<?php echo urlencode($local['nombre']); ?>&local_rubro=<?php echo urlencode($local['rubro']); ?>" class="card-link">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="card.image">
                                                    <img src="./private/visualizar_imagen.php?local_id=<?php echo $local['id']; ?>" alt="Imagen del local" class="img-fluid">
                                                </div>
                                                <h4 class="card-title"><?php echo $local['nombre']; ?></h4>
                                                <p class="card-text">
                                                    <?php echo $local['rubro']; ?><br>
                                                    <?php echo $local['ubicacion']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p>No se encontraron locales que coincidan con los filtros.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="pagination-container mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </main>
    <?php include './includes/footer.php'; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
