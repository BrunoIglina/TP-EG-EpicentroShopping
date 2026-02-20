<?php
require_once './includes/navigation_history.php';
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
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Reporte de Promociones - Epicentro Shopping', 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }

        function ReportTable($header, $data) {
            $this->SetFont('Arial', 'B', 10);
            $widths = array(15, 60, 25, 25, 30, 20, 25, 15); 
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C');
            }
            $this->Ln();
            $this->SetFont('Arial', '', 9);
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
    $pdf->Output('D', 'Reporte_Promociones_' . date('Y-m-d') . '.pdf'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/forms.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
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
    </style>
</head>
<body>
        <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>
    
    <div class="form-wrapper">
        <div class="container">
            <h2 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Reportes de Promociones</h2>
            
            <div class="filter-card">
                <h5 class="mb-3" style="color: #667eea; font-weight: 600;">
                    <i class="bi bi-funnel"></i> Filtros de Búsqueda
                </h5>
                
                <form method="GET" action="reportesDueño.php">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="<?php echo htmlspecialchars($fecha_inicio ?? ''); ?>">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="<?php echo htmlspecialchars($fecha_fin ?? ''); ?>">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estadoPromo" class="form-label">Estado</label>
                            <select class="form-select" id="estadoPromo" name="estadoPromo">
                                <option value="">Todos</option>
                                <option value="Aprobada" <?php echo ($estadoPromo == 'Aprobada') ? 'selected' : ''; ?>>Aprobada</option>
                                <option value="Pendiente" <?php echo ($estadoPromo == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="Denegada" <?php echo ($estadoPromo == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <button type="submit" name="generate_pdf" class="btn btn-secondary">
                            <i class="bi bi-file-pdf"></i> Generar PDF
                        </button>
                        <a href="reportesDueño.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="table-card">
                <h5 class="mb-3" style="color: #667eea; font-weight: 600;">
                    <i class="bi bi-table"></i> Resultados (<?php echo $result->num_rows; ?> promociones)
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Texto</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Categoría</th>
                                <th>Local ID</th>
                                <th>Estado</th>
                                <th>Usos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $estadoClass = '';
                                    switch($row['estadoPromo']) {
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
                                    
                                    echo "<tr>";
                                    echo "<td><strong>" . htmlspecialchars($row['id']) . "</strong></td>";
                                    echo "<td style='text-align: left;'>" . htmlspecialchars($row['textoPromo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fecha_fin']) . "</td>";
                                    echo "<td><span class='badge bg-info'>" . htmlspecialchars($row['categoriaCliente']) . "</span></td>";
                                    echo "<td>" . htmlspecialchars($row['local_id']) . "</td>";
                                    echo "<td><span class='badge " . $estadoClass . "'>" . htmlspecialchars($row['estadoPromo']) . "</span></td>";
                                    echo "<td><strong>" . htmlspecialchars($row['totalPromos']) . "</strong></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-muted'>
                                        <i class='bi bi-inbox'></i> No hay promociones que coincidan con los filtros
                                      </td></tr>";
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include './includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>