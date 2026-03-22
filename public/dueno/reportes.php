<?php
// public/dueno/reportes.php

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/fix_header.css">
    <title>Reportes de Promociones | Epicentro Shopping</title>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <div class="container mt-5 pt-5">
        <div class="d-flex align-items-center gap-3 mb-4">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
          <h2 class="text-center mb-4 m-0">Reporte de Uso de Promociones</h2>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted">A continuación se detalla la cantidad de clientes que han utilizado efectivamente cada una de tus promociones.</p>
                        
                        <?php if (empty($reporte)): ?>
                            <div class="alert alert-info">Aún no hay datos de uso para tus promociones.</div>
                        <?php else: ?>
                            <table class="table table-hover mt-3">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Local</th>
                                        <th>Promoción</th>
                                        <th class="text-center">Total de Usos (Canjes)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reporte as $dato): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($dato['local_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($dato['textoPromo']); ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6">
                                                    <?php echo $dato['usos']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>