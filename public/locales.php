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
    <title>Epicentro Shopping - Locales</title>
</head>
<body>
    
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="locales-section">
            <h1>Locales de Epicentro Shopping</h1>
            <div class="locales-list">
                <?php foreach ($locales as $local) {?>
                    
                    <div class="card text-center">
                        <div class="card-body">
                            <img class="card-img-top" src="">
                            <h4 class="card-title"><?php echo $local['nombre']?></h4>
                            <p class="card-text"> 
                                <?php echo $local['rubro']?><br>                                    <?php echo $local['ubicacion']?>
                            </p>
                        </div>
                    </div>    

                <?php }?>
                
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>