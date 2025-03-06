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

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}


$total_result_sql = "
    SELECT COUNT(*) AS total
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
    $total_result_sql .= " AND promociones.categoriaCliente IN (";
    for ($i = 0; $i <= $indice_categoria_cliente; $i++) {
        $total_result_sql .= "'" . $categorias[$i] . "'";
        if ($i < $indice_categoria_cliente) {
            $total_result_sql .= ", ";
        }
    }
    $total_result_sql .= ")";
}
if (isset($_GET['nombre_local']) && $_GET['nombre_local'] != '') {
    $nombre_local = $conn->real_escape_string($_GET['nombre_local']);
    $total_result_sql .= " AND locales.nombre LIKE '%$nombre_local%'";
}
if (isset($_GET['rubro']) && $_GET['rubro'] != '') {
    $rubro = $conn->real_escape_string($_GET['rubro']);
    $total_result_sql .= " AND locales.rubro = '$rubro'";
}
if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = (int)$_GET['local_id'];
    $total_result_sql .= " AND locales.id = $local_id";
}

$total_result = $conn->query($total_result_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <div class="wrapper">
        <?php include '../includes/header.php'; ?>
        <main class="container">
            <div class="row">
                <div class="col-mdform-group">
                            <input type="text" name="nombre_local" class="form-control" placeholder="Buscar por nombre del local...">
                        </div>
                        <div class="form-group">
                            <select name="rubro" class="form-control">
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
                        </div>
                        <div class="form-group">
                            <input type="number" name="local_id" class="form-control" placeholder="Buscar por ID del local...">
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
                <div class="col-md-9">
                    <div id="promocionesContainer">
                        <?php
                        $currentLocal = '';
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($currentLocal != $row["local_nombre"]) {
                                    if ($currentLocal != '') {
                                        echo "</div>";
                                    }
                                    echo "<div class='card mb-3'>";
                                    echo "<div class='card-header'><h2 class='card-title'>" . $row["local_nombre"] . "</h2></div>";
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
                                echo "<button type='submit' class='btn btn-success'>Pedir Promoción</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p>No hay promociones que coincidan con los criterios de búsqueda.</p>";
                        }
                        ?>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if($page > 1){ echo "?page=" . ($page - 1); } ?>">Anterior</a>
                            </li>
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if($page == $i){ echo 'active'; } ?>">
                                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if($page < $total_pages){ echo "?page=" . ($page + 1); } ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
