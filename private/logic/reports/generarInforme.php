<?php
// Limpiamos cualquier salida previa (evita el error de "headers already sent")
if (ob_get_length()) ob_clean();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/vendor/autoload.php';

// Verificación de seguridad
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    die("Acceso denegado");
}

$local_id = filter_input(INPUT_GET, 'local_id', FILTER_VALIDATE_INT);
$conn = getDB();

// 1. Obtener nombre del local
$stmt = $conn->prepare("SELECT nombre FROM locales WHERE id = ?");
$stmt->bind_param("i", $local_id);
$stmt->execute();
$local = $stmt->get_result()->fetch_assoc();
$nombre_local = $local['nombre'] ?? 'Desconocido';
$stmt->close();

// 2. Obtener promociones y el conteo de usos ACEPTADOS
// Usamos una subconsulta para asegurarnos de que el conteo sea exacto por cada ID
$query = "SELECT 
            p.id,
            p.textoPromo, 
            p.fecha_inicio, 
            p.fecha_fin, 
            (SELECT COUNT(*) 
             FROM promociones_cliente pc 
             WHERE pc.idPromocion = p.id 
             AND pc.estado = 'aceptada') AS veces_usada
          FROM promociones p
          WHERE p.local_id = ?"; // Atributo exacto de tu tabla

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $local_id);
$stmt->execute();
$promociones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 3. Generar PDF
$pdf = new FPDF();
$pdf->AddPage();

// Cabecera prolija
$pdf->SetFont('Arial', 'B', 16);
$titulo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Informe de Uso de Promociones');
$pdf->Cell(0, 10, $titulo, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Local: ' . $nombre_local), 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de tabla con color (gris claro)
$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(85, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Promoción'), 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Inicio', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fin', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'Usos', 1, 0, 'C', true);
$pdf->Ln();

// Listado de datos
$pdf->SetFont('Arial', '', 10);
foreach ($promociones as $p) {
    // Convertimos caracteres especiales para FPDF
    $texto = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $p['textoPromo']);

    $pdf->Cell(85, 8, $texto, 1);
    $pdf->Cell(30, 8, $p['fecha_inicio'], 1, 0, 'C');
    $pdf->Cell(30, 8, $p['fecha_fin'], 1, 0, 'C');

    // Si es 0, lo resaltamos o lo dejamos simple
    $pdf->Cell(25, 8, $p['veces_usada'], 1, 0, 'C');
    $pdf->Ln();
}

// Limpiamos búfer antes de enviar el archivo
if (ob_get_length()) ob_end_clean();

$pdf->Output('D', 'Informe_Promociones_' . str_replace(' ', '_', $nombre_local) . '.pdf');
exit();
