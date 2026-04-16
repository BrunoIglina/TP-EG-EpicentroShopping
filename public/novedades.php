<?php
require_once __DIR__ . '/../includes/navigation_history.php';
require_once __DIR__ . '/../includes/security_headers.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje_error'] = "Iniciar sesión para observar las novedades";
    header("Location: index.php");
    exit();
}

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Diccionario para traducir los meses al español
$meses_espanol = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Epicentro Shopping - Novedades</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/tarjetas.css">

    <link rel="stylesheet" href="public/css/wrapper.css">
</head>

<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../includes/header.php'; ?>

        <main id="main-content" class="container-fluid py-4">
            
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Novedades
                    </h1>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <div class="row g-4">
                <?php if (empty($novedades)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-warning d-inline-block px-5">No hay novedades disponibles en este momento.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($novedades as $novedad): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                
                                <div style="height: 200px; overflow: hidden;">
                                    <img src="index.php?vista=imagen&novedad_id=<?= htmlspecialchars($novedad['id']); ?>"
                                         class="card-img-top w-100 h-100" 
                                         alt="Imagen de la novedad" 
                                         style="object-fit: cover;">
                                </div>

                                <div class="card-body d-flex flex-column text-center p-4">

                                    <div class="mb-3">
                                        <span class="badge bg-secondary text-uppercase" style="font-size: 0.75rem;">
                                            <?php 
                                                $fecha_h = trim($novedad['fecha_hasta']);
                                                if (!empty($fecha_h) && strpos($fecha_h, '0000-00-00') === false) {
                                                    try {
                                                        $dt_h = new DateTime($fecha_h);
                                                        $dia = $dt_h->format('d');
                                                        $mes = $meses_espanol[$dt_h->format('m')];
                                                        $anio = $dt_h->format('Y');
                                                        echo "Vigente hasta: $dia de $mes de $anio";
                                                    } catch (Exception $e) {
                                                        echo "Vigente hasta: " . htmlspecialchars($fecha_h);
                                                    }
                                                } else {
                                                    echo "Vigente hasta: No especificada";
                                                }
                                            ?>
                                        </span>
                                    </div>

                                    <p class="card-text text-dark mb-4 texto-novedad-clamp">
                                        <?= htmlspecialchars($novedad['textoNovedad']); ?>
                                    </p>

                                    <div class="mt-auto pt-3 border-top">
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar3"></i> 
                                            <?php 
                                                $fecha_d = trim($novedad['fecha_desde']);
                                                if (!empty($fecha_d) && strpos($fecha_d, '0000-00-00') === false) {
                                                    try {
                                                        $dt_d = new DateTime($fecha_d);
                                                        $dia = $dt_d->format('d');
                                                        $mes = $meses_espanol[$dt_d->format('m')];
                                                        $anio = $dt_d->format('Y');
                                                        echo "Fecha Desde: $dia de $mes de $anio";
                                                    } catch (Exception $e) {
                                                        echo htmlspecialchars($fecha_d);
                                                    }
                                                } else {
                                                    echo "Fecha no especificada";
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <nav aria-label="Navegación de páginas" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?vista=novedades&page=<?= $current_page - 1; ?>">Anterior</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?vista=novedades&page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?vista=novedades&page=<?= $current_page + 1; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </main>

        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>