<?php

    error_reporting(0);

    require("php/config.php");
    require("php/connect.php");
    require("php/methods.php");
    
    startSession();

    if (!$logged) header("Location: .");

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'cadastrar':
                $add_cliente = true;
                $error = false;

                if (!empty($_POST['cpf']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
                    
                    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
                    $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
                    $len_phone = strlen($phone);

                    if (strlen($cpf) != 11) $error = "CPF inválido."; else
                    if ($len_phone < 10 || $len_phone > 11) $error = "Telefone inválido."; else
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $error = "E-mail inválido."; else
                    if (getCliente($cpf) !== false) $error = "Cliente já cadastrado."; else 
                    if (!addCliente($cpf, $_POST['name'], $_POST['email'], $phone)) $error = "Falha no banco de dados.";
                    else header("Location: ?");

                } else {
                    $error = "Preencha todos os campos.";
                }
                break;
            case "remover":
                
                if (isset($_GET['id'])) {
                    if (($cliente = getClienteById($_GET['id']))) {
                        if (($imoveis = getImoveisByLocador($cliente[1]))) {
                            foreach ($imoveis as $i) {
                                removeLocacoesByImovel($i[0]);
                                removeImovel($i[0]);
                            }
                        }

                        if (($locacoes = getLocacoesByLocatario($cliente[1]))) {
                            foreach ($locacoes as $l) {
                                setImovelAlugado($l[2], 0);
                                removeLocacao($l[0]);
                            }
                        }

                        removeCliente($cliente[0]); 
                    }

                    header("Location: ?");
                }

                break;
        }
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <title><?php echo $name; ?> - Clientes</title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">

        <script type="text/javascript" src="js/js.js"></script>
    </head>

    <body onload="searchMethodChanged();">

<?php printHeader(1); ?>

        <div class="center" style="margin-top: 25px; margin-bottom: 25px; text-align: center; min-height: 400px;">
            <div class="division" style="width: 300px;">
                <div class="box">
                    <div class="title_box">Cadastrar cliente</div>
                    <form method="post" action="?action=cadastrar">
                        <input type="text" class="input" name="cpf" placeholder="CPF" maxlength="14" <?php if (isset($add_cliente)) echo 'value="'.$_POST['cpf'].'" '; ?>required>
                        <input type="text" class="input" name="name" placeholder="Nome" maxlength="128" <?php if (isset($add_cliente)) echo 'value="'.$_POST['name'].'" '; ?>required>
                        <input type="email" class="input" name="email" placeholder="E-mail" maxlength="128" <?php if (isset($add_cliente)) echo 'value="'.$_POST['email'].'" '; ?>required>
                        <input type="tel" class="input" name="phone" placeholder="Telefone" maxlength="15" <?php if (isset($add_cliente)) echo 'value="'.$_POST['phone'].'" '; ?>required>
<?php  

    if (isset($add_cliente) && $error !== false) {
        echo "<div class=\"form_error\">$error</div>";
    }

?>
                        
                        <input type="submit" class="button" value="Cadastrar">
                    </form>
                </div>
                <div class="box">
                    <div class="title_box">Busca</div>
                    <form method="get" action="#results">
                        <input type="hidden" name="action" value="search">
                        <select name="method" class="input" id="search_method" onchange="searchMethodChanged();">
                            <option value="3" <?php if (isset($_GET['method']) && $_GET['method'] == 3) echo "selected"; ?>>Por CPF</option>
                            <option value="4" <?php if (isset($_GET['method']) && $_GET['method'] == 4) echo "selected"; ?>>Por nome</option>
                        </select>
                        <input name="value" type="text" placeholder="" id="search_input" class="input" value="<?php if (isset($_GET['value']) && $_GET['method'] != 0) echo $_GET['value']; ?>" required>
<?php if (isset($_GET['action']) && $_GET['action'] == 'search') echo '<a href="?#results" title="Cancelar busca">Cancelar busca</a><br>'; ?>
                        
                        <input type="submit" class="button" value="Buscar">
                    </form>
                </div>
            </div><div class="division" style="width: 600px; margin-left: 20px;">
                <div class="box" id="results">
                    <div class="title_box">Clientes</div>

<?php 

    $results = 0;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    if (!isset($_GET['action']) || $_GET['action'] != 'search') {$clientes = getAllClientes($page); $results = getCountAllClientes();} else
    if ($_GET['method'] == 0 && ($cliente = getClienteById($_GET['value']))) {$clientes = [$cliente]; $results = 1;} else
    if ($_GET['method'] == 3 && ($cliente = getCliente(preg_replace('/[^0-9]/', '', $_GET['value'])))) {$clientes = [$cliente]; $results = 1;} else
    if ($_GET['method'] == 4) {$clientes = getClientesByName($_GET['value']); $results = getCountClientesByName($_GET['value']);} 
    else $clientes = false;    

    if (!$clientes) echo 'Nenhum cliente encontrado.'; else {
        
        echo '<span class="title1">Clientes encontrados: </span>'.$results.'<hr>';

        foreach ($clientes as $c) {

            echo '
                    <div class="box_cliente">
                        <div><span class="title1" style="font-size: 12pt;">'.$c[2].'</span><a onclick="confirmBox(\'Remover cliente?\', \'Todos os registros relacionados a este cliente também serão removidos.\', \'?action=remover&id='.$c[0].'\');" style="float: right;" title="Remover cliente">Remover cliente</a></div><hr>
                        <div style="position: relative;">
                            <span class="title1">CPF: </span>'.formatCPF($c[1]).'<br>
                            <span class="title1">E-mail: </span><a href="mailto:'.$c[3].'" title="E-mail">'.$c[3].'</a><br>
                            <span class="title1">Telefone: </span><a href="tel:'.$c[4].'" title="Telefone">'.formatPhone($c[4]).'</a><br>
                            <div style="position: absolute; right: 0; bottom: 0; text-align: right; line-height: 2;">
                                <a href=".?action=search&method=1&value='.$c[1].'#results" title="Buscar imóveis deste cliente">Imóveis</a><br>
                                <a href="locacoes.php?action=search&method=2&value='.$c[1].'#results" title="Buscar locações deste cliente">Locações</a>
                            </div>
                        </div>
                    </div>
                 ';

        }

    }

?>

                    <hr style="margin-top: 30px;">
                    
<?php
    
    echo '<div style="display: inline-block; width: 150px;">';

    if ($page > 1) {$_GET['page'] = $page - 1; echo '<a href="?'.http_build_query($_GET).'#results">« Anterior</a>';}
                            
    echo '</div>Página '.$page.'<div style="display: inline-block; width: 150px;">';
                            
    if ($page * $per_page < $results) {$_GET['page'] = $page + 1; echo '<a href="?'.http_build_query($_GET).'#results">Próxima »</a>';}
    
    echo '</div>';

?>     

                </div>
            </div>
        </div>


<?php printFooter(); ?>

    </body>

</html>