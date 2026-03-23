<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/queries/promociones.queries.php';
require_once __DIR__ . '/queries/locales.queries.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'Dueno') {
    $_SESSION['error'] = "Acceso denegado. No tienes permisos de dueño.";
    header("Location: index.php");
    exit();
}

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'eliminar_promo':
        procesar_eliminar_promo();
        break;
    case 'gestionar_solicitud':
        procesar_gestionar_solicitud();
        break;
    case 'crear_promocion':
        procesar_crear_promocion();
        break;
    case 'descargar_pdf_reporte':
        procesar_descargar_pdf_reporte();
        break;
    default:
        header("Location: index.php?vista=dueno_panel");
        exit();
}

function procesar_eliminar_promo()
{
    $promo_id = intval($_POST['promo_id'] ?? 0);

    if ($promo_id <= 0) {
        $_SESSION['error'] = "ID de promoción no válido.";
        header("Location: index.php?vista=dueno_promociones");
        exit();
    }

    if (eliminar_promocion_dueno($promo_id, $_SESSION['user_id'])) {
        $_SESSION['success'] = "La promoción fue eliminada correctamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al eliminar la promoción.";
    }

    header("Location: index.php?vista=dueno_promociones");
    exit();
}

function procesar_gestionar_solicitud()
{
    $promo_id = intval($_POST['promo_id'] ?? 0);
    $cliente_id = intval($_POST['cliente_id'] ?? 0);
    $estado_nuevo = $_POST['estado_nuevo'] ?? '';

    if ($promo_id <= 0 || $cliente_id <= 0 || !in_array($estado_nuevo, ['aceptar', 'rechazar'])) {
        $_SESSION['error'] = "Datos de solicitud no válidos.";
        header("Location: index.php?vista=dueno_solicitudes");
        exit();
    }

    $estado_db = ($estado_nuevo === 'aceptar') ? 'aceptada' : 'rechazada';

    if (gestionar_solicitud_query($promo_id, $cliente_id, $estado_db)) {
        $_SESSION['success'] = "La solicitud fue " . $estado_db . " correctamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al gestionar la solicitud.";
    }

    header("Location: index.php?vista=dueno_solicitudes");
    exit();
}

function procesar_crear_promocion()
{
    $local_id = intval($_POST['local_id'] ?? 0);
    $textoPromo = trim($_POST['textoPromo'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $categoriaCliente = $_POST['categoriaCliente'] ?? '';
    $diasArray = $_POST['diasSemana'] ?? [];
    $diasSemana = implode(", ", $diasArray);

    if ($local_id <= 0 || empty($textoPromo) || empty($fecha_inicio) || empty($fecha_fin) || empty($diasSemana) || empty($categoriaCliente)) {
        $_SESSION['error'] = "Por favor, completá todos los campos y seleccioná al menos un día.";
        header("Location: index.php?vista=dueno_promocion_agregar");
        exit();
    }

    if (crear_promocion_query($local_id, $textoPromo, $fecha_inicio, $fecha_fin, $diasSemana, $categoriaCliente)) {
        $_SESSION['success'] = "¡Promoción agregada con éxito!";
        header("Location: index.php?vista=dueno_promociones");
    } else {
        $_SESSION['error'] = "Error en la base de datos al crear la promoción.";
        header("Location: index.php?vista=dueno_promocion_agregar");
    }
    exit();
}

function procesar_descargar_pdf_reporte()
{
    $user_id = (int)$_SESSION['user_id'];
    
    $filters = [
        'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
        'fecha_fin'    => $_POST['fecha_fin']    ?? '',
        'estadoPromo'  => $_POST['estadoPromo']  ?? '',
        'local_id'     => $_POST['local_id']     ?? '',
    ];
    
    $reportes = getReportesPromos($user_id, $filters) ?? [];
    generarPdfReporte($reportes);
    exit();
}

function generarPdfReporte(array $reportes)
{
    require_once __DIR__ . '/../../private/lib/vendor/setasign/fpdf/fpdf.php';
    
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Reporte de Promociones - Epicentro Shopping', 0, 1, 'C');
            $this->Ln(5);
        }
        
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
        
        function ReportTable($header, $data)
        {
            $this->SetFont('Arial', 'B', 10);
            $widths = array(15, 40, 20, 20, 20, 20, 20, 20);
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C');
            }
            $this->Ln();
            $this->SetFont('Arial', '', 8);
            foreach ($data as $row) {
                $this->Cell($widths[0], 6, $row[0], 1);
                $this->Cell($widths[1], 6, substr($row[1], 0, 20), 1);
                $this->Cell($widths[2], 6, $row[2], 1);
                $this->Cell($widths[3], 6, $row[3], 1);
                $this->Cell($widths[4], 6, $row[4], 1);
                $this->Cell($widths[5], 6, $row[5], 1);
                $this->Cell($widths[6], 6, $row[6], 1);
                $this->Cell($widths[7], 6, $row[7], 1);
                $this->Ln();
            }
        }
    }
    
    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $header = array('ID', 'Texto', 'Inicio', 'Fin', 'Categoria', 'Local', 'Estado', 'Usos');
    $data = [];
    
    foreach ($reportes as $row) {
        $data[] = array(
            (string)$row['id'],
            (string)$row['textoPromo'],
            (string)$row['fecha_inicio'],
            (string)$row['fecha_fin'],
            (string)$row['categoriaCliente'],
            (string)$row['local_nombre'],
            (string)$row['estadoPromo'],
            (string)$row['usos']
        );
    }
    
    $pdf->ReportTable($header, $data);
    $pdf->Output('D', 'Reporte_Promociones_' . date('Y-m-d') . '.pdf');
}
