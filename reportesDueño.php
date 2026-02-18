<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

require_once './config/database.php';
$conn = getDB();

$user_id = $_SESSION['user_id'];

$params = [$user_id];
$types = "i";
$conditions = [];

$fecha_inicio = filter_input(INPUT_GET, 'fecha_inicio', FILTER_SANITIZE_STRING);
$fecha_fin = filter_input(INPUT_GET, 'fecha_fin', FILTER_SANITIZE_STRING);
$estadoPromo = filter_input(INPUT_GET, 'estadoPromo', FILTER_SANITIZE_STRING);

if (!empty($fecha_inicio)) {
    $conditions[] = "p.fecha_inicio >= ?";
    $params[] = $fecha_inicio;
    $types .= "s";
}
if (!empty($fecha_fin)) {
    $conditions[] = "p.fecha_fin <= ?";
    $params[] = $fecha_fin;
    $types .= "s";
}
if (!empty($estadoPromo)) {
    $conditions[] = "p.estadoPromo = ?";
    $params[] = $estadoPromo;
    $types .= "s";
}

$sql = "SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.categoriaCliente, p.local_id, p.estadoPromo,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        WHERE p.local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";

if (count($conditions) > 0) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_GET['generate_pdf'])) {
    require('./lib/vendor/setasign/fpdf/fpdf.php');

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
            $widths = array(20, 60, 30, 30, 50, 30, 20, 30); 
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
    
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            $row['id'], 
            substr($row['textoPromo'], 0, 30), 
            $row['fecha_inicio'], 
            $row['fecha_fin'], 
            $row['categoriaCliente'], 
            $row['local_id'], 
            $row['estadoPromo'], 
            $row['totalPromos']
        );
    }
    $pdf->ReportTable($header, $data);
    $pdf->Output('D', 'Reporte_Promociones.pdf'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
<link rel="stylesheet" href="./css/footer.css">
<link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Reportes de Promociones</title>
</head>
<body>
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
        <main class="container my-4">
            <h2 class="text-center my-4">Reportes de Promociones</h2>
            <form method="GET" action="reportesDueño.php">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio ?? ''); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin ?? ''); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="estadoPromo">Estado</label>
                        <select class="form-control" id="estadoPromo" name="estadoPromo">
                            <option value="">Todos</option>
                            <option value="Aprobada" <?php echo ($estadoPromo == 'Aprobada') ? 'selected' : ''; ?>>Aprobada</option>
                            <option value="Pendiente" <?php echo ($estadoPromo == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="Denegada" <?php echo ($estadoPromo == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
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
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['textoPromo']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fecha_fin']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['categoriaCliente']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['local_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['estadoPromo']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['totalPromos']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay promociones</td></tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
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