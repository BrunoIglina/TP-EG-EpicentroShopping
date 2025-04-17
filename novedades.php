<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje_error'] = "Iniciar sesión para observar las novedades";
    header("Location: index.php");
    exit();
}

include './private/functions_novedades.php';

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$novedades = array_slice(get_novedades_permitidas($_SESSION['user_id'], $_SESSION['user_tipo'],$_SESSION['user_categoria']), $offset, $limit);
$total_novedades = count(get_novedades_permitidas($_SESSION['user_id'], $_SESSION['user_tipo'],$_SESSION['user_categoria']));
$total_pages = ceil($total_novedades / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/tarjetas.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Novedades</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main class="container-fluid">
            <h2 class="text-center my-5">Novedades</h2>
            <p>Explora las últimas novedades y noticias de Epicentro Shopping.</p>

            <?php if (!$novedades) { ?>
                <div class="alert alert-warning">No hay novedades disponibles</div>
            <?php } else { ?>
                <?php foreach ($novedades as $novedad) { ?>
                    <div class="mb-4">
                        <div class="card w-100">
                            <div class="card-img-top bg-light" style="height: 250px;">
                                <img src="./private/visualizar_imagen.php?novedad_id=<?php echo htmlspecialchars($novedad['id']); ?>" 
                                        alt="Imagen de la novedad" class="img-fluid h-100 w-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Desde:</strong> <?php echo htmlspecialchars($novedad['fecha_desde']); ?></p>
                                <p class="mb-2"><strong>Hasta:</strong> <?php echo htmlspecialchars($novedad['fecha_hasta']); ?></p>
                                <p class="mb-0"><?php echo htmlspecialchars($novedad['textoNovedad']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="pagination-container">
                    <ul class="pagination justify-content-center mt-4">
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
            <?php } ?>
        </main>

        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
