<?php
require_once __DIR__ . '/../../private/logic/functions/functions_usuarios.php';

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$clientes = get_usuarios_pendientes('Cliente', $limit, $offset);
$total_clientes = get_total_usuarios_pendientes('Cliente');
$total_pages = ceil($total_clientes / $limit);
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="assets/logo2.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/styles_fondo_and_titles.css">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/back_button.css">
	<link rel="stylesheet" href="css/fix_header.css">
	<title>Epicentro Shopping - Aprobar Clientes</title>
</head>

<body>
	<div class="wrapper">
		<?php include __DIR__ . '/../../includes/header.php'; ?>
		<main class="container-fluid">
			<?php include __DIR__ . '/../../includes/back_button.php'; ?>
			<h2 class="text-center my-4">Aprobar Clientes</h2>

			<?php if (isset($_SESSION['success'])): ?>
				<div class="alert alert-success text-center">
					<?php echo htmlspecialchars($_SESSION['success']);
					unset($_SESSION['success']); ?>
				</div>
			<?php endif; ?>

			<form id="approvalForm" method="POST" action="index.php">
				<input type="hidden" name="modulo" value="admin">
				<input type="hidden" name="accion" value="estado_usuario">
				<input type="hidden" name="estado" value="1"> <input type="hidden" name="tipo_usuario" value="cliente">

				<table class="table table-striped">
					<thead>
						<tr>
							<th>Email</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($clientes)): ?>
							<?php foreach ($clientes as $row): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['email']); ?></td>
									<td>
										<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal"
											data-id="<?php echo $row['id']; ?>"
											data-email="<?php echo htmlspecialchars($row['email']); ?>">Aprobar</button>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="2" class="text-center">No hay clientes pendientes de aprobación</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</form>

			<div class="pagination-container mt-4">
				<ul class="pagination justify-content-center">
					<li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
						<a class="page-link"
							href="index.php?vista=admin_aprobar_clientes&page=<?php echo $page - 1; ?>">Anterior</a>
					</li>
					<?php for ($i = 1; $i <= $total_pages; $i++): ?>
						<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
							<a class="page-link"
								href="index.php?vista=admin_aprobar_clientes&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						</li>
					<?php endfor; ?>
					<li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
						<a class="page-link"
							href="index.php?vista=admin_aprobar_clientes&page=<?php echo $page + 1; ?>">Siguiente</a>
					</li>
				</ul>
			</div>
		</main>
		<?php include __DIR__ . '/../../includes/footer.php'; ?>
	</div>

	<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirmar Aprobación</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<div class="modal-body">
					¿Está seguro de que desea aprobar al cliente con email <strong id="modalEmail"></strong>?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		let selectedId = null;
		$(document).ready(function() {
			$('#confirmModal').on('show.bs.modal', function(event) {
				let button = $(event.relatedTarget);
				selectedId = button.data('id');
				$('#modalEmail').text(button.data('email'));
			});

			$('#confirmActionBtn').on('click', function() {
				if (selectedId) {
					let form = $('#approvalForm');
					$('<input>').attr({
						type: 'hidden',
						name: 'usuario_id',
						value: selectedId
					}).appendTo(form);
					form.submit();
				}
			});
		});
	</script>
</body>

</html>