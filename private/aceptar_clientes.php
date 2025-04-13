<?php
include(__DIR__ . '/../env/shopping_db.php');

$id = $_POST['id'];

$sql = "UPDATE usuarios SET validado = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
   echo"Cliente aprobado correctamente.";
   header("Location: ../admin_aprobar_clientes.php");
   
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
