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

        <title><?php echo $name; ?> - Corretores</title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">
        
        <script type="text/javascript" src="js/js.js"></script>
    </head>

    <body>

<?php printHeader(3); ?>

    <div class="center" style="margin-top: 25px; margin-bottom: 25px; text-align: center; min-height: 400px;">

<?php

    if ($logged && $user_id === 0) {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'cadastrar':
                    $add_corretor = true;
                    $error = false;

                    if (!empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['creci']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
                    
                        $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
                        $len_phone = strlen($phone);

                        if (strlen($_POST['password']) < 5) $error = "Senha inválida."; else
                        if ($_POST['password'] !== $_POST['password1']) $error = "Senhas não coincidem."; else 
                        if (getCorretor($_POST['user']) !== false) $error = "Usuário já existente."; else
                        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $error = "E-mail inválido."; else
                        if ($len_phone < 10 || $len_phone > 11) $error = "Telefone inválido"; else
                        if (!is_numeric($_POST['creci']) || strlen($_POST['phone'])<3) $error = "CRECI inválido"; else 
                        if (!addCorretor($_POST['user'], $_POST['password'], $_POST['name'], $_POST['creci'], $_POST['email'], $phone)) $error = "Falha no banco de dados.";
                        else header("Location: ?");
                        

                    } else {
                        $error = "Preencha todos os campos.";
                    }

                    break;
                case 'remover':
                    if (isset($_GET['id'])) {
                        removeCorretor($_GET['id']); 
                        header("Location: ?");
                    }

                    break;
            }
        }

        echo '  <div class="division" style="width: 300px; margin-right: 20px;">
                    <div class="box">
                        <div class="title_box">Cadastrar corretor</div>
                        <form method="post" action="?action=cadastrar">
                            <input type="text" name="user" placeholder="Usuário" class="input" maxlength="128"'; if (isset($add_corretor) && $error) echo ' value="'.$_POST['user'].'"'; echo ' required>
                            <input type="password" name="password" placeholder="Senha" class="input" minlength="5" maxlength="128" required>
                            <input type="password" name="password1" placeholder="Repita a senha" class="input" minlength="5" maxlength="128" style="margin-bottom: 25px;" required>
                            <input type="text" name="name" placeholder="Nome" class="input" maxlength="128"'; if (isset($add_corretor) && $error) echo ' value="'.$_POST['name'].'"'; echo ' required>
                            <input type="text" name="creci" placeholder="CRECI" class="input"  maxlength="16"'; if (isset($add_corretor) && $error) echo ' value="'.$_POST['creci'].'"'; echo ' required>
                            <input type="email" name="email" placeholder="E-mail" class="input" maxlength="128"'; if (isset($add_corretor) && $error) echo ' value="'.$_POST['email'].'"'; echo ' required>
                            <input type="tel" name="phone" placeholder="Telefone" class="input" maxlength="15"'; if (isset($add_corretor) && $error) echo ' value="'.$_POST['phone'].'"'; echo ' required>';

        if (isset($add_corretor) && $error !== false) {
            echo "<div class=\"form_error\">$error</div>";
        }

        echo '              <input type="submit" class="button" value="Cadastrar">
                        </form>
                    </div>
                </div><div class="division" style="width: 600px;">';
    } else {
        echo '<div class="division" style="width: 920px;">';
    }

?>
            <div class="box">
                <div class="title_box">Nossos corretores</div>
<?php

    $corretores = getAllCorretores();

    if ($corretores) {
        if (!$logged) echo 'Para cadastrar o seu imóvel ou alugar um disponível, entre em contato com um dos corretores a seguir:<br><br>';

        foreach ($corretores as $c) {
            echo '<div class="box_corretor"><b>'.$c[3].'</b><br>CRECI '.$c[4].'<hr><span class="title1">Email: </span><a href="mailto:'.$c[5].'" title="E-mail">'.$c[5].'</a><br><span class="title1">Telefone: </span><a href="tel:'.$c[6].'" title="Telefone">'.formatPhone($c[6]).'</a>';
            
            if ($logged && $user_id === 0) {
                echo '<hr><a onclick="confirmBox(\'Remover corretor?\', \'Você realmente deseja remover este corretor?\', \'?action=remover&id='.$c[0].'\');" title="Remover corretor">Remover corretor</a>';
            }

            echo '</div>';
        } 

    } else {
        echo "Nenhum corretor cadastrado.";
    }

?>
            </div>
        </div>
    </div>

<?php printFooter(); ?>

    </body>

</html>