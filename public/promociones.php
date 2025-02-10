<?php
session_start();

include '../env/shopping_db.php';

$categoriaCliente = isset($_SESSION['user_categoria']) ? $_SESSION['user_categoria'] : null;

$categorias = ['Inicial', 'Medium', 'Premium'];
$sql = "
    SELECT 
        locales.nombre AS local_nombre, 
        promociones.id AS promo_id,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        locales.rubro,
        locales.id AS local_id
    FROM 
        locales 
    INNER JOIN 
        promociones 
    ON 
        locales.id = promociones.local_id 
    WHERE 
        promociones.estadoPromo = 'Aprobada'
";

if ($categoriaCliente) {
    $indice_categoria_cliente = array_search($categoriaCliente, $categorias);
    $sql .= " AND promociones.categoriaCliente IN (";
    for ($i = 0; $i <= $indice_categoria_cliente; $i++) {
        $sql .= "'" . $categorias[$i] . "'";
        if ($i < $indice_categoria_cliente) {
            $sql .= ", ";
        }
    }
    $sql .= ")";
}

if (isset($_GET['nombre_local']) && $_GET['nombre_local'] != '') {
    $nombre_local = $conn->real_escape_string($_GET['nombre_local']);
    $sql .= " AND locales.nombre LIKE '%$nombre_local%'";
}

if (isset($_GET['rubro']) && $_GET['rubro'] != '') {
    $rubro = $conn->real_escape_string($_GET['rubro']);
    $sql .= " AND locales.rubro = '$rubro'";
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = (int)$_GET['local_id'];
    $sql .= " AND locales.id = $local_id";
}

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="sidebar">
            <h3>Filtrar por:</h3>
            <form method="GET" action="">
                <input type="text" name="nombre_local" placeholder="Buscar por nombre del local...">
                <select name="rubro">
                    <option value="">Todos los rubros</option>
                    <option value="Ropa">Ropa</option>
                    <option value="Electrónica">Electrónica</option>
                    <option value="Joyería">Joyería</option>
                    <option value="Calzado">Calzado</option>
                    <option value="Librería">Librería</option>
                    <option value="Alimentos">Alimentos</option>
                    <option value="Bebidas">Bebidas</option>
                    <option value="Farmacia">Farmacia</option>
                    <option value="Deportes">Deportes</option>
                    <option value="Muebles">Muebles</option>
                    <option value="Hogar">Hogar</option>
                    <option value="Automóviles">Automóviles</option>
                    <option value="Belleza">Belleza</option>
                    <option value="Viajes">Viajes</option>
                    <option value="Otros">Otros</option>
                </select>
                <input type="number" name="local_id" placeholder="Buscar por ID del local...">
                <button type="submit">Filtrar</button>
            </form>
        </div>
        <div id="promocionesContainer">
            <?php
            $currentLocal = '';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($currentLocal != $row["local_nombre"]) {
                        if ($currentLocal != '') {
                            echo "</div>";
                        }
                        echo "<div class='card'>";
                        echo "<h2 class='card-title'>" . $row["local_nombre"] . "</h2>";
                        $currentLocal = $row["local_nombre"];
                    }
                    echo "<div class='card-body'>";
                    echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                    echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                    echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                    echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>";
                    echo "<p>Rubro: " . $row["rubro"] . "</p>";
                    echo "<p>ID del Local: " . $row["local_id"] . "</p>";
                    echo "<form method='POST' action='pedir_promocion.php'>";
                    echo "<input type='hidden' name='promo_id' value='" . $row["promo_id"] . "'>";
                    echo "<button type='submit'>Pedir Promoción</button>";
                    echo "</form>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No hay promociones que coincidan con los criterios de búsqueda.</p>";
            }
            ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
