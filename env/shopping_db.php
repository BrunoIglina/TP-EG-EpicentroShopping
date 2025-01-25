<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "shopping_db";
$port = 3309;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>