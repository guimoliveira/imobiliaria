<?php

    error_reporting(0);

    require("php/config.php");
    require("php/connect.php");
    require("php/methods.php");
    
    startSession();

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <title><?php echo $name; ?> - Sobre</title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">
    </head>

    <body>

<?php printHeader(4); ?>

        <div class="center" style="margin-top: 25px; margin-bottom: 25px; text-align: center; min-height: 400px; width: 920px;">
        
            <div class="box">
                <div class="title_box">Sobre nós</div>
                <?php echo $about; ?>
            </div>

        </div>


<?php printFooter(); ?>

    </body>

</html>