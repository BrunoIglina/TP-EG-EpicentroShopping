<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueño') {
    header("Location: index.php");
    exit();
}

include '../env/shopping_db.php';

$user_id = $_SESSION['user_id'];

$filters = [];
$sql = "SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.categoriaCliente, p.local_id, p.estadoPromo,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        WHERE p.local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";

if (isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] != '') {
    $filters[] = "p.fecha_inicio >= '" . $conn->real_escape_string($_GET['fecha_inicio']) . "'";
}
if (isset($_GET['fecha_fin']) && $_GET['fecha_fin'] != '') {
    $filters[] = "p.fecha_fin <= '" . $conn->real_escape_string($_GET['fecha_fin']) . "'";
}
if (isset($_GET['estadoPromo']) && $_GET['estadoPromo'] != '') {
    $filters[] = "p.estadoPromo = '" . $conn->real_escape_string($_GET['estadoPromo']) . "'";
}

if (count($filters) > 0) {
    $sql .= " AND " . implode(" AND ", $filters);
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_GET['generate_pdf'])) {
    require('../lib/fpdf/fpdf.php');

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Reporte de Promociones', 0, 1, 'C');
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }

        function ReportTable($header, $data) {
            $this->SetFont('Arial', 'B', 12);
            $widths = array(20, 60, 30, 30, 50, 30, 20, 30, 20); 
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($widths[$i], 7, $header[$i], 1);
            }
            $this->Ln();
            $this->SetFont('Arial', '', 12);
            foreach ($data as $row) {
                for ($i = 0; $i < count($row); $i++) {
                    $this->Cell($widths[$i], 6, $row[$i], 1);
                }
                $this->Ln();
            }
        }
    }

    $pdf = new PDF('L', 'mm', 'A4'); 
    $pdf->AddPage();
    $header = array('ID', 'Texto', 'Inicio', 'Fin', 'Categoria', 'Local', 'Estado', 'Usos');
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = array($row['id'], $row['textoPromo'], $row['fecha_inicio'], $row['fecha_fin'], $row['categoriaCliente'], $row['local_id'], $row['estadoPromo'], $row['totalPromos']);
    }
    $pdf->ReportTable($header, $data);
    $pdf->Output('D', 'Reporte_Promociones.pdf'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Reportes de Promociones</title>
</head>
<body>
    <div class="wrapper">
    <?php include '../includes/header.php'; ?>
        <main class="container my-4">
            <h1>Reportes de Promociones</h1>
            <form method="GET" action="reportesDueño.php">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="estadoPromo">Estado</label>
                        <select class="form-control" id="estadoPromo" name="estadoPromo">
                            <option value="">Todos</option>
                            <option value="Aprobada" <?php echo (isset($_GET['estadoPromo']) && $_GET['estadoPromo'] == 'Aprobada') ? 'selected' : ''; ?>>Aprobada</option>
                            <option value="Pendiente" <?php echo (isset($_GET['estadoPromo']) && $_GET['estadoPromo'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="Denegada" <?php echo (isset($_GET['estadoPromo']) && $_GET['estadoPromo'] == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <button type="submit" name="generate_pdf" class="btn btn-secondary">Imprimir PDF</button>
            </form>
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Texto de la Promoción</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Categoría Cliente</th>
                            <th>Local ID</th>
                            <th>Estado</th>
                            <th>Total de Usos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['textoPromo'] . "</td>";
                                echo "<td>" . $row['fecha_inicio'] . "</td>";
                                echo "<td>" . $row['fecha_fin'] . "</td>";
                                echo "<td>" . $row['categoriaCliente'] . "</td>";
                                echo "<td>" . $row['local_id'] . "</td>";
                                echo "<td>" . $row['estadoPromo'] . "</td>";
                                echo "<td>" . $row['totalPromos'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No hay promociones</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>