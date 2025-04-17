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

$items_per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($filtered_locales);
$total_pages = ceil($total_items / $items_per_page);
$start_index = ($current_page - 1) * $items_per_page;
$paginated_locales = array_slice($filtered_locales, $start_index, $items_per_page);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/tarjetas.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="./css/footer.css">
    <title>Epicentro Shopping - Locales</title>
</head>
<body>
<div class="wrapper">
    <?php include './includes/header.php'; ?>
    <main>
        <div class="container-fluid">

            <div class="row">
                <form class="d-flex w-100" method="GET">
                    <div class="flex-grow-1 me-2">
                        <input type="text" name="nombre_local" class="form-control" placeholder="Buscar por nombre del local..." value="<?php echo isset($_GET['nombre_local']) ? $_GET['nombre_local'] : ''; ?>">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-3" style="padding: 0.5rem;">
                    <h3>Filtrar por:</h3>
                    <form>
                        <div class="form-group">
                            <select name="rubro" class="form-control">
                                <option value="">Todos los rubros</option>
                                <?php foreach ($rubros as $label => $value) { ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="number" name="local_id" class="form-control" placeholder="Buscar por ID del local..." min="1">
                        </div>
                        <button type="submit" class="btn btn-secondary align-self-start mt-2">Filtrar</button>
                    </form>
                </div>

                <div class="col-md-9">

                    <div class="row">
                        <?php foreach ($paginated_locales as $local) { ?>
                            <div class="col-md-4 col-sm-12" style="padding: .5rem;">
                                <a href="promociones.php?local_id=<?php echo $local['id']; ?>" class="card-link">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="card-image">
                                                <?php echo '<img src="./private/visualizar_imagen.php?local_id=' . $local['id'] . '" alt="Imagen de el local">'; ?>
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
                    </div>

                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                    
                </div>
            </div>

        </div>
    </main>
    <?php include './includes/footer.php'; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>