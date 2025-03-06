<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: ../public/index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../lib/vendor/phpmailer/phpmailer/vendor/autoload.php'); // Ruta actualizada
use FPDF;

// Verificar si el parámetro local_id está presente en la URL
if (!isset($_GET['local_id'])) {
    die('ID del local no proporcionado');
}

// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "shopping_db";
$port = 3309;

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del local
$local_id = $_GET['local_id'];

// Obtener el nombre del local
$sql_local = "SELECT nombre FROM locales WHERE id = ?";
$stmt_local = $conn->prepare($sql_local);
$stmt_local->bind_param("i", $local_id);
$stmt_local->execute();
$result_local = $stmt_local->get_result();
$local = $result_local->fetch_assoc();
$nombre_local = $local['nombre'];

// Consulta para obtener los datos de las promociones y el uso de las mismas para el local específico
$sql = "SELECT p.id, p.textoPromo, l.nombre AS local, p.fecha_inicio, p.fecha_fin, COUNT(pc.idPromocion) AS veces_usada, GROUP_CONCAT(u.email SEPARATOR ', ') AS emails_usados
        FROM promociones p
        JOIN locales l ON p.local_id = l.id
        LEFT JOIN promociones_cliente pc ON p.id = pc.idPromocion
        LEFT JOIN usuarios u ON pc.idCliente = u.id
        WHERE l.id = ? AND pc.estado = 'aceptada'
        GROUP BY p.id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $local_id);
$stmt->execute();
$result = $stmt->get_result();
$promociones = $result->fetch_all(MYSQLI_ASSOC);

// Depuración: Imprimir los resultados obtenidos
if (empty($promociones)) {
    die("No se encontraron promociones usadas para el local: " . $nombre_local);
}

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Informe de Promociones Usadas - Local: ' . $nombre_local);
$pdf->Ln(20);

foreach ($promociones as $promo) {
    $pdf->Cell(0, 10, 'Local: ' . $promo['local'], 0, 1);
    $pdf->Cell(0, 10, 'Texto de la Promoción: ' . $promo['textoPromo'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha Inicio: ' . $promo['fecha_inicio'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha Fin: ' . $promo['fecha_fin'], 0, 1);
    $pdf->Cell(0, 10, 'Veces Usada: ' . $promo['veces_usada'], 0, 1);
    $pdf->Cell(0, 10, 'Emails de clientes que usaron la promoción: ' . $promo['emails_usados'], 0, 1);
    $pdf->Ln(10);
}

$pdf->Output('D', 'informe_promociones_local_' . $local_id . '.pdf');

$stmt->close();
$stmt_local->close();
$conn->close();
?>
