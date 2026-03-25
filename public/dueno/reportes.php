<?php
// public/dueno/reportes.php

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
	header("Location: index.php");
	exit();
}

// Obtener valores de filtros actuales
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
	<link rel="icon" type="image/png" href="public/assets/logo2.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./public/css/header.css">
	<link rel="stylesheet" href="./public/css/footer.css">
	<link rel="stylesheet" href="./public/css/forms.css">
	<link rel="stylesheet" href="./public/css/back_button.css">
	<link rel="stylesheet" href="./public/css/fix_header.css">

	<title>Epicentro Shopping - Reportes de Promociones</title>
	<style>
		.filter-card {
			background: white;
			padding: 2rem;
			border-radius: 12px;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
			margin-bottom: 2rem;
		}

		.table-card {
			background: white;
			padding: 2rem;
			border-radius: 12px;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
		}

		.table thead th {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border: none;
			font-weight: 600;
			text-align: center;
			vertical-align: middle;
		}

		.table tbody td {
			vertical-align: middle;
			text-align: center;
		}

		.badge {
			padding: 0.5em 0.75em;
			font-weight: 600;
		}

		.btn-gradient {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border: none;
			color: white;
			font-weight: 600;
		}

		.btn-gradient:hover {
			background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
			color: white;
		}
	</style>
</head>

<body>
	<?php include __DIR__ . '/../../includes/header.php'; ?>
	<?php include __DIR__ . '/../../includes/back_button.php'; ?>

	<div class="form-wrapper">
		<div class="container">
			<h2 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Reportes de Promociones</h2>

			<section class="filter-card">
				<h3 class="mb-3" style="color: #667eea; font-weight: 600;">
					<i class="bi bi-funnel"></i> Filtros de Búsqueda
				</h3>

				<form method="GET" action="index.php">
					<input type="hidden" name="vista" value="dueno_reportes">

					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="local_id" class="form-label">Local</label>
							<select class="form-select" id="local_id" name="local_id">
								<option value="">Todos</option>
								<?php foreach ($locales_dueno as $local): ?>
									<option value="<?php echo $local['id']; ?>"
										<?php echo ($local_id == $local['id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($local['nombre']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-md-3 mb-3">
							<label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
							<input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
								value="<?php echo htmlspecialchars($fecha_inicio); ?>">
						</div>

						<div class="col-md-3 mb-3">
							<label for="fecha_fin" class="form-label">Fecha de Fin</label>
							<input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
								value="<?php echo htmlspecialchars($fecha_fin); ?>">
						</div>

						<div class="col-md-3 mb-3">
							<label for="estadoPromo" class="form-label">Estado</label>
							<select class="form-select" id="estadoPromo" name="estadoPromo">
								<option value="">Todos</option>
								<option value="Aprobada" <?php echo ($estadoPromo == 'Aprobada') ? 'selected' : ''; ?>>Aprobada</option>
								<option value="Pendiente" <?php echo ($estadoPromo == 'Pendiente') ? 'selected' : ''; ?>>Pendiente
								</option>
								<option value="Denegada" <?php echo ($estadoPromo == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
							</select>
						</div>
					</div>

					<div class="d-flex gap-2 flex-wrap">
						<button type="submit" class="btn btn-gradient">
							<i class="bi bi-search"></i> Filtrar
						</button>
						<a href="index.php?vista=dueno_reportes" class="btn btn-outline-secondary">
							<i class="bi bi-x-circle"></i> Limpiar Filtros
						</a>
					</div>
				</form>

				<form method="POST" action="index.php" class="mt-3">
					<input type="hidden" name="modulo" value="dueno">
					<input type="hidden" name="accion" value="descargar_pdf_reporte">
					<input type="hidden" name="local_id" value="<?php echo htmlspecialchars($local_id); ?>">
					<input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
					<input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
					<input type="hidden" name="estadoPromo" value="<?php echo htmlspecialchars($estadoPromo); ?>">
					<button type="submit" class="btn btn-secondary">
						<i class="bi bi-file-pdf"></i> Generar PDF
					</button>
				</form>
			</section>

			<section class="table-card">
				<h3 class="mb-3" style="color: #667eea; font-weight: 600;">
					<i class="bi bi-table"></i> Resultados (<?php echo $total; ?> promociones)
				</h3>

				<div class="table-responsive">
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>Local</th>
								<th>Promoción</th>
								<th>Fecha Inicio</th>
								<th>Fecha Fin</th>
								<th>Categoría</th>
								<th>Estado</th>
								<th>Usos</th>
							</tr>
						</thead>
						<tbody>
							<?php if (count($reportes) > 0): ?>
								<?php foreach ($reportes as $row):
									$estadoClass = '';
									switch ($row['estadoPromo']) {
										case 'Aprobada':
											$estadoClass = 'bg-success';
											break;
										case 'Pendiente':
											$estadoClass = 'bg-warning';
											break;
										case 'Denegada':
											$estadoClass = 'bg-danger';
											break;
									}
								?>
									<tr>
										<td><?php echo htmlspecialchars($row['local_nombre']); ?></td>
										<td style="text-align: left;"><?php echo htmlspecialchars($row['textoPromo']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
										<td><span class="badge bg-info"><?php echo htmlspecialchars($row['categoriaCliente']); ?></span></td>
										<td><span
												class="badge <?php echo $estadoClass; ?>"><?php echo htmlspecialchars($row['estadoPromo']); ?></span>
										</td>
										<td><strong><?php echo htmlspecialchars($row['usos']); ?></strong></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="7" class="text-center text-muted">
										<i class="bi bi-inbox"></i> No hay promociones que coincidan con los filtros
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>

		<?php include __DIR__ . '/../../includes/footer.php'; ?>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>