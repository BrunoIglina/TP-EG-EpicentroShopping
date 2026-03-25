<?php
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
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<link rel="icon" type="image/png" href="./public/assets/logo2.png">
	<link rel="stylesheet" href="./public/css/fix_header.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./public/css/header.css">
	<link rel="stylesheet" href="./public/css/footer.css">
	<link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
	<link rel="stylesheet" href="./public/css/back_button.css">
	<link rel="stylesheet" href="./public/css/buttons.css">

	<title>Epicentro Shopping - Mis Promociones</title>
</head>

<body>
	<div class="wrapper">
		<?php include __DIR__ . '/../../includes/header.php'; ?>
		<main class="container-fluid">
			<section class="admin-section">
				<div class="row align-items-center mb-5 mt-3">
					<div class="col-2 col-md-1 text-start">
						<?php include __DIR__ . '/../../includes/back_button.php'; ?>
					</div>

					<div class="col-8 col-md-10">
						<h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
							Mis Promociones
						</h2>
					</div>

					<div class="col-2 col-md-1"></div>
				</div>

				<div class="d-flex justify-content-center gap-2 mb-3">
					<button class="btn btn-primary btn-sm" onclick="location.href='index.php?vista=dueno_promocion_agregar'">
						Agregar Promoción
					</button>
					<button class="btn btn-secondary btn-sm" onclick="location.href='index.php?vista=dueno_reportes'">
						Ver Reportes
					</button>
				</div>

				<?php if (isset($_SESSION['success'])): ?>
					<div class="alert alert-success alert-dismissible fade show">
						<?php
						echo htmlspecialchars($_SESSION['success']);
						unset($_SESSION['success']);
						?>
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				<?php endif; ?>

				<?php if (isset($_SESSION['error'])): ?>
					<div class="alert alert-danger alert-dismissible fade show">
						<?php
						echo htmlspecialchars($_SESSION['error']);
						unset($_SESSION['error']);
						?>
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				<?php endif; ?>

				<?php if (empty($promociones)): ?>
					<div class="alert alert-warning text-center">No hay promociones registradas</div>
				<?php else: ?>
					<div class="table-responsive-lg">
						<table class="table table-striped table-bordered">
							<thead class="thead-dark">
								<tr>
									<th>Texto Promoción</th>
									<th>Fecha Inicio</th>
									<th>Fecha Fin</th>
									<th>Días Semana</th>
									<th>Categoría</th>
									<th>Razón Social</th>
									<th>Estado</th>
									<th>Usos</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($promociones as $row): ?>
									<tr>
										<td><?php echo htmlspecialchars($row['textoPromo']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
										<td><?php echo htmlspecialchars($row['diasSemana']); ?></td>
										<td>
											<span class="badge bg-info">
												<?php echo htmlspecialchars($row['categoriaCliente']); ?>
											</span>
										</td>
										<td><?php echo htmlspecialchars($row['local_nombre']); ?></td>
										<td>
											<?php
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
											<span class="badge <?php echo $estadoClass; ?>">
												<?php echo htmlspecialchars($row['estadoPromo']); ?>
											</span>
										</td>
										<td><strong><?php echo htmlspecialchars($row['totalPromos']); ?></strong></td>
										<td>
											<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#confirmModal" data-id="<?php echo $row['id']; ?>"
												data-texto="<?php echo htmlspecialchars($row['textoPromo']); ?>">
												Eliminar
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<nav>
						<ul class="pagination justify-content-center">
							<li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
								<a class="page-link" href="index.php?vista=dueno_promociones&page=<?php echo $page - 1; ?>">Anterior</a>
							</li>
							<?php for ($i = 1; $i <= $total_pages; $i++): ?>
								<li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
									<a class="page-link" href="index.php?vista=dueno_promociones&page=<?php echo $i; ?>"><?php echo $i; ?></a>
								</li>
							<?php endfor; ?>
							<li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
								<a class="page-link" href="index.php?vista=dueno_promociones&page=<?php echo $page + 1; ?>">Siguiente</a>
							</li>
						</ul>
					</nav>
				<?php endif; ?>
			</section>
		</main>

		<?php include __DIR__ . '/../../includes/footer.php'; ?>
	</div>

	<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true"
		role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h3>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<div class="modal-body">
					¿Está seguro de que desea eliminar la promoción?
					<div class="alert alert-info mt-3">
						<strong id="modalTexto"></strong>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

					<form id="deleteForm" action="index.php" method="POST" style="display: inline;">
						<input type="hidden" name="modulo" value="dueno">
						<input type="hidden" name="accion" value="eliminar_promo">
						<input type="hidden" name="promo_id" id="modalPromoId">
						<button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		const confirmModal = document.getElementById('confirmModal');
		confirmModal.addEventListener('show.bs.modal', function(event) {
			const button = event.relatedTarget;
			const id = button.getAttribute('data-id');
			const texto = button.getAttribute('data-texto');

			document.getElementById('modalPromoId').value = id;
			document.getElementById('modalTexto').textContent = texto;
		});
	</script>
</body>

</html>