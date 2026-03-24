<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

$fecha_inicio = $filters['fecha_inicio'] ?? '';
$fecha_fin = $filters['fecha_fin'] ?? '';
$estadoPromo = $filters['estadoPromo'] ?? '';
$local_id = $filters['local_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Reportes Detallados de Promociones | Epicentro Shopping</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/forms.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">

    <style>
        /* Ajuste de contraste para el degradado (colores más saturados) */
        .btn-gradient,
        .table thead th {
            background: linear-gradient(135deg, #4e54c8 0%, #3f2b96 100%) !important;
            color: #ffffff !important;
            border: none;
        }

        .filter-card,
        .table-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }

        /* Corregimos el contraste de los badges */
        .badge-categoria {
            background-color: #0d6efd;
            color: white;
        }

        /* Azul fuerte */
        .badge-aprobada {
            background-color: #198754;
            color: white;
        }

        .badge-pendiente {
            background-color: #856404;
            color: #fff;
        }

        /* Dorado oscuro para contraste */
        .badge-denegada {
            background-color: #dc3545;
            color: white;
        }

        .form-label {
            font-weight: 700;
            color: #343a40;
        }
    </style>
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main id="main-content" class="container py-4">
        <div class="row align-items-center mb-5 mt-3">
            <div class="col-2 col-md-1 text-start">
                <?php include __DIR__ . '/../../includes/back_button.php'; ?>
            </div>
            <div class="col-8 col-md-10">
                <h1 class="text-center m-0 fw-bold text-uppercase h2">Reportes de Promociones</h1>
            </div>
            <div class="col-2 col-md-1"></div>
        </div>

        <div class="form-wrapper">
            <section class="filter-card" aria-labelledby="filtros-titulo">
                <h2 id="filtros-titulo" class="h5 mb-4 fw-bold text-primary">
                    <i class="bi bi-funnel-fill"></i> Filtros de Búsqueda
                </h2>

                <form method="GET" action="index.php">
                    <input type="hidden" name="vista" value="dueno_reportes">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="local_id" class="form-label">Seleccionar Local</label>
                            <select class="form-select" id="local_id" name="local_id">
                                <option value="">Todos los locales</option>
                                <?php foreach ($locales_dueno as $local): ?>
                                    <option value="<?= $local['id']; ?>" <?= ($local_id == $local['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($local['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="fecha_inicio" class="form-label">Desde fecha</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio); ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="fecha_fin" class="form-label">Hasta fecha</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin); ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="estadoPromo" class="form-label">Estado de Promo</label>
                            <select class="form-select" id="estadoPromo" name="estadoPromo">
                                <option value="">Todos los estados</option>
                                <option value="Aprobada" <?= ($estadoPromo == 'Aprobada') ? 'selected' : ''; ?>>Aprobada</option>
                                <option value="Pendiente" <?= ($estadoPromo == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="Denegada" <?= ($estadoPromo == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-gradient px-4 shadow-sm">
                            <i class="bi bi-search"></i> APLICAR FILTROS
                        </button>
                        <a href="index.php?vista=dueno_reportes" class="btn btn-outline-dark px-4">
                            LIMPIAR
                        </a>
                    </div>
                </form>

                <form method="POST" action="index.php" class="mt-3 pt-3 border-top">
                    <input type="hidden" name="modulo" value="dueno">
                    <input type="hidden" name="accion" value="descargar_pdf_reporte">
                    <button type="submit" class="btn btn-dark shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i> DESCARGAR INFORME PDF
                    </button>
                </form>
            </section>

            <section class="table-card" aria-labelledby="resultados-titulo">
                <h2 id="resultados-titulo" class="h5 mb-4 fw-bold text-primary">
                    <i class="bi bi-table"></i> Resultados obtenidos (<?= $total; ?>)
                </h2>

                <div class="table-responsive rounded-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Local</th>
                                <th scope="col">Promoción</th>
                                <th scope="col">Vigencia</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Usos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total > 0): ?>
                                <?php foreach ($reportes as $row):
                                    $estadoClass = match ($row['estadoPromo']) {
                                        'Aprobada' => 'badge-aprobada',
                                        'Pendiente' => 'badge-pendiente',
                                        'Denegada' => 'badge-denegada',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($row['local_nombre']); ?></td>
                                        <td class="text-start"><?= htmlspecialchars($row['textoPromo']); ?></td>
                                        <td class="small"><?= htmlspecialchars($row['fecha_inicio']); ?> al <?= htmlspecialchars($row['fecha_fin']); ?></td>
                                        <td><span class="badge badge-categoria px-3"><?= htmlspecialchars($row['categoriaCliente']); ?></span></td>
                                        <td><span class="badge <?= $estadoClass ?> px-3"><?= htmlspecialchars($row['estadoPromo']); ?></span></td>
                                        <td class="fs-5 fw-bold"><?= htmlspecialchars($row['usos']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="py-5 text-muted">No hay datos para los filtros seleccionados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>