<?php
$servername = "sql105.infinityfree.com";
$username = "if0_38549771";
$password = "qhsR3hE0gwwHMo";
$dbname = "if0_38549771_shopping_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>