<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $local_id = $_POST['local_id']; 
    $textoPromo = $_POST['textoPromo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $diasSemana = $_POST['diasSemana'];
    $categoriaCliente = $_POST['categoriaCliente'];
    $estadoPromo = 'Pendiente';  

    $conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $hoy = date('Y-m-d');
    if ($fecha_inicio < $hoy) {
        $_SESSION['error'] = "La fecha de inicio no puede ser anterior a hoy.";
        header("Location: ../public/darAltaPromos.php");
        exit();
    } else if ($fecha_fin < $fecha_inicio) {
        $_SESSION['error'] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
        header("Location: ../public/darAltaPromos.php");
        exit();
    }

    $sql = "INSERT INTO promociones (local_id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, estadoPromo)
            VALUES ('$local_id', '$textoPromo', '$fecha_inicio', '$fecha_fin', '$diasSemana', '$categoriaCliente', '$estadoPromo')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Promoción agregada exitosamente. El Administrador debe evaluar dicha promoción, puede ver su estado en la seccion Mis Promociones.');
                window.location.href = '../public/misPromos.php';
              </script>";
    } else {
        echo "<script>
                alert('Error en la consulta: " . $conn->error . "');
                window.location.href = '../public/darAltaPromos.php';
              </script>";
    }

    $conn->close();
} else {
    echo "<script>
            alert('Método de solicitud no válido.');
            window.location.href = '../public/darAltaPromos.php';
          </script>";
}
?>