<?php
    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');



$id = $_POST['id'];

$sql = "UPDATE usuarios SET validado = 1 WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Dueño aprobado correctamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>