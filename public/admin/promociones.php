<?php
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<link rel="icon" type="image/png" href="assets/logo2.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/styles_fondo_and_titles.css">
	<link rel="stylesheet" href="css/admin_promociones.css">
	<link rel="stylesheet" href="css/back_button.css">
	<link rel="stylesheet" href="css/fix_header.css">
	<title>Epicentro Shopping - Administración de Promociones</title>
</head>

<body>
	<div class="wrapper">
		<?php include __DIR__ . '/../../includes/header.php'; ?>
		<main class="container-fluid">
			<?php include __DIR__ . '/../../includes/back_button.php'; ?>
			<section class="admin-section">
				<h2 class="text-center my-2">Aprobar promociones pendientes</h2>

				<?php if (isset($_SESSION['success'])): ?>
					<div class="alert alert-success text-center">
						<?php echo htmlspecialchars($_SESSION['success']);
						unset($_SESSION['success']); ?>
					</div>
				<?php endif; ?>

				<div class="table-responsive">
					<table class="table table-bordered text-center align-middle">
						<thead class="table-dark">
							<tr>
								<th>Texto de la Promoción</th>
								<th>Fecha de Inicio</th>
								<th>Fecha de Fin</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($promociones)): ?>
								<?php foreach ($promociones as $row): ?>
									<tr>
										<td><?php echo htmlspecialchars($row['textoPromo']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
										<td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
										<td>
											<button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#confirmModal'
												data-id='<?php echo $row['id']; ?>' data-action='aprobar'>Aprobar</button>
											<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#confirmModal'
												data-id='<?php echo $row['id']; ?>' data-action='rechazar'>Rechazar</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan='4'>No hay promociones pendientes</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

				<div class="pagination-container mt-4">
					<ul class="pagination justify-content-center">
						<li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
							<a class="page-link" href="index.php?vista=admin_promociones&page=<?php echo $page - 1; ?>">Anterior</a>
						</li>
						<?php for ($i = 1; $i <= $total_pages; $i++): ?>
							<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
								<a class="page-link" href="index.php?vista=admin_promociones&page=<?php echo $i; ?>"><?php echo $i; ?></a>
							</li>
						<?php endfor; ?>
						<li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
							<a class="page-link" href="index.php?vista=admin_promociones&page=<?php echo $page + 1; ?>">Siguiente</a>
						</li>
					</ul>
				</div>
			</section>
		</main>
		<?php include __DIR__ . '/../../includes/footer.php'; ?>
	</div>

	<form id="actionForm" method="POST" action="index.php">
		<input type="hidden" name="modulo" value="admin">
		<input type="hidden" name="accion" value="estado_promocion">
	</form>

	<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirmar Acción</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					¿Está seguro de que desea <span id="modalAction"></span> esta promoción?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#confirmModal').on('show.bs.modal', function(event) {
				var button = $(event.relatedTarget);
				var promocionId = button.data('id');
				var action = button.data('action');
				$(this).find('#modalAction').text(action);

				$('#confirmActionBtn').off('click').on('click', function() {
					var form = $('#actionForm');
					// Limpiamos inputs previos si los hubiera para no duplicar
					form.find('input[name="accion_crud"], input[name="promocion_id"]').remove();

					form.append('<input type="hidden" name="accion_crud" value="' + action + '">');
					form.append('<input type="hidden" name="promocion_id" value="' + promocionId + '">');
					form.submit();
				});
			});
		});
	</script>
</body>

</html>