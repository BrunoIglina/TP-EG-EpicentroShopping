<?php

include '../private/functions_locales.php';

$locales = get_all_locales();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/locales.css">
    <title>Epicentro Shopping - Locales</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="container-fluid">
            <div class="row">

                <?php foreach ($locales as $local) {?>
                    <div class="col-md-4 col-sm-12" style = "padding: .5rem;">    
                        <a href="promociones.php?local_id=<?php echo $local['id']; ?>" class="card-link">
                            <div class="card text-center">
                                <div class="card-body">
                                    <img class="card-img-top" src="">
                                    <h4 class="card-title"><?php echo $local['nombre']?></h4>
                                    <p class="card-text"> 
                                        <?php echo $local['rubro']?><br>
                                        <?php echo $local['ubicacion']?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php }?>
                     
            </div>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
