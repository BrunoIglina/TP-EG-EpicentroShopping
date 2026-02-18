<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/vendor/autoload.php';
require_once __DIR__ . '/../../lib/vendor/setasign/fpdf/fpdf.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: ../../index.php");
    exit();
}

$local_id = filter_input(INPUT_GET, 'local_id', FILTER_VALIDATE_INT);

if (!$local_id) {
    die('ID del local no válido');
}

$conn = getDB();

$stmt_local = $conn->prepare("SELECT nombre FROM locales WHERE id = ?");
$stmt_local->bind_param("i", $local_id);
$stmt_local->execute();
$result_local = $stmt_local->get_result();
$local = $result_local->fetch_assoc();
$stmt_local->close();

if (!$local) {
    die('Local no encontrado');
}

$nombre_local = $local['nombre'];

$stmt = $conn->prepare("SELECT p.id, p.textoPromo, l.nombre AS local, p.fecha_inicio, p.fecha_fin, 
        COUNT(pc.idPromocion) AS veces_usada, GROUP_CONCAT(u.email SEPARATOR ', ') AS emails_usados
        FROM promociones p
        JOIN locales l ON p.local_id = l.id
        LEFT JOIN promociones_cliente pc ON p.id = pc.idPromocion
        LEFT JOIN usuarios u ON pc.idCliente = u.id
        WHERE l.id = ? AND pc.estado = 'aceptada'
        GROUP BY p.id");

$stmt->bind_param("i", $local_id);
$stmt->execute();
$result = $stmt->get_result();
$promociones = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($promociones)) {
    die("No se encontraron promociones usadas para el local: " . htmlspecialchars($nombre_local));
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Informe de Promociones Usadas', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Local: ' . utf8_decode($nombre_local), 0, 1);
$pdf->Ln(5);

foreach ($promociones as $promo) {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'Promocion: ' . utf8_decode($promo['textoPromo']), 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Fecha Inicio: ' . $promo['fecha_inicio'], 0, 1);
    $pdf->Cell(0, 6, 'Fecha Fin: ' . $promo['fecha_fin'], 0, 1);
    $pdf->Cell(0, 6, 'Veces Usada: ' . $promo['veces_usada'], 0, 1);
    $pdf->MultiCell(0, 6, 'Clientes: ' . utf8_decode($promo['emails_usados']));
    $pdf->Ln(5);
}

$pdf->Output('D', 'informe_promociones_local_' . $local_id . '.pdf');