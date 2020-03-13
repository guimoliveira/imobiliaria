<?php

    error_reporting(0);

    require("php/config.php");
    require("php/connect.php");
    require("php/methods.php");
    
    startSession();
    
    if (isset($_GET['action']) && $_GET['action'] == "logoff") {
        $_SESSION['logged'] = false;
        
        header("Location: .");
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <title><?php echo $name; ?> - Login</title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">
    </head>

    <body>

<?php 

    printHeader(5);

    $error = false;

    if (isset($_POST['user']) || isset($_POST['password'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];

        if ($user == "") $error = "Preencha o campo usuário."; else
        if ($password == "") $error = "Preencha o campo senha."; else 
        if ($user == $admin_username) {
            if ($password != $admin_password) $error = "Senha incorreta."; else {
                $_SESSION['user_id'] = 0;
                $_SESSION['logged'] = true;

                header("Location: .");
            }
        } else {
            $corretor = getCorretor($user);

            if (!$corretor) $error = "Usuário inválido."; else 
            if ($corretor[2] != $password) $error = "Senha incorreta."; else {
                $_SESSION['user_id'] = $corretor[0];
                $_SESSION['logged'] = true;

                header("Location: .");
            }
        }        
    }

?>

        <div class="center" style="margin-top: 60px; margin-bottom: 25px; text-align: center; min-height: 400px; width: 400px;">
        
            <div class="box">
                <div class="title_box">Login</div>
                Acesso disponível somente à funcionários.

                <form method="post" action="">
                    <input type="text" name="user" class="input" placeholder="Usuário" style="margin-top: 15px;" value="<?php if(isset($user)) echo $user; ?>" autofocus>
                    <input type="password" name="password" class="input" placeholder="Senha">
                    
<?php 

    if ($error) echo "<div class=\"form_error\">$error</div>";

?>

                    <input type="submit" class="button" value="Entrar">
                </form>
            </div>

        </div>

    </body>

</html>