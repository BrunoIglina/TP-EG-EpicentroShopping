<?php
$servername = "sql309.infinityfree.com";
$username = "if0_38547175";
$password = "3TfglbMleCxk";
$dbname = "if0_38547175_shopping_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>