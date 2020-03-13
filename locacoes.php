<?php

    error_reporting(0);

    require("php/config.php");
    require("php/connect.php");
    require("php/methods.php");
    
    startSession();

    if (!$logged) header("Location: ."); else {

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'cadastrar':
                    $add_locacao = true;
                    $error = false;

                    if (!empty($_POST['codigo']) && !empty($_POST['cpf'])) {

                        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
                        
                        if (!is_numeric($_POST['codigo']) || $_POST['codigo'] < 0) $error = "Código do imóvel inválido."; else
                        if (strlen($cpf) != 11) $error = "CPF inválido."; else 
                        if (!($locatario = getCliente($cpf))) $error = "CPF não cadastrado."; else 
                        if (!($imovel = getImovel($_POST['codigo']))) $error = "Imóvel não cadastrado."; else 
                        if ($locatario[0] == $imovel[2]) $error = "Locador e locatário são o mesmo."; else
                        if ($imovel[9]) $error = "Imóvel já alugado."; else
                        if (!addLocacao($locatario[0], $imovel[0])) $error = "Falha no banco de dados."; else {

                            setImovelAlugado($imovel[0], 1);

                            header("Location: ?");
                        }

                    } else {
                        $error = "Preencha todos os campos.";
                    }
                    break;
                case 'encerrar':

                    if (!empty($_GET['id'])) {
                        $locacao = getLocacao($_GET['id']);

                        if ($locacao) {
                            encerrarLocacao($_GET['id']);
                            setImovelAlugado($locacao[2], 0);
                        }

                        header("Location: ?");
                    }

                    break;
            }
        }
            

    }

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <title><?php echo $name; ?> - Locações</title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">

        <script type="text/javascript" src="js/js.js"></script>
    </head>

    <body onload="searchMethodChanged();">

<?php printHeader(2); ?>

        <div class="center" style="margin-top: 25px; margin-bottom: 25px; text-align: center; min-height: 400px; width: 920px;">
            <div class="division" style="width: 300px;">
                <div class="box">
                    <div class="title_box">Cadastrar locação</div>
                    <form method="post" action="?action=cadastrar">
                        <input type="text" name="codigo" class="input" placeholder="Código do imóvel"<?php if (isset($add_locacao) && $error) echo ' value="'.$_POST['codigo'].'"'; else if (isset($_GET['action']) && $_GET['action'] == 'alugar') echo ' value="'.$_GET['id'].'"'; ?> required>
                        <input type="text" name="cpf" class="input" placeholder="CPF do locatário"<?php if (isset($add_locacao) && $error) echo ' value="'.$_POST['cpf'].'"';?> maxlength="14" required <?php if (isset($_GET['action']) && $_GET['action'] == 'alugar') echo 'autofocus'; ?>>

<?php

    if (isset($add_locacao) && $error !== false) {
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
                            <option selected value="0" <?php if (isset($_GET['method']) && $_GET['method'] == 0) echo "selected"; ?>>Por imóvel</option>
                            <option value="2" <?php if (isset($_GET['method']) && $_GET['method'] == 2) echo "selected"; ?>>Por locatário</option>
                        </select>
                        <input name="value" type="text" placeholder="" id="search_input" class="input" value="<?php if (isset($_GET['value'])) echo $_GET['value']; ?>" required>
<?php if (isset($_GET['action']) && $_GET['action'] == 'search') echo '<a href="?#results" title="Cancelar busca">Cancelar busca</a><br>'; ?>
                        <input type="submit" class="button" value="Buscar">
                    </form>
                </div>
            </div><div class="division" style="width: 600px; margin-left: 20px;">
                <div class="box" id="results">
                    <div class="title_box">
                        
<?php

    $results = 0;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $filter = $logged ? (isset($_GET['filter']) ? intval($_GET['filter']) : 0) : 1;
    
    $get = $_GET;
    $get['page'] = 1;
    
    $get['filter'] = 0;
    echo '<a href="?'.http_build_query($get).'#results" class="link';
    if ($filter === 0) echo '_active';
    $get['filter'] = 1;
    echo '" title="Mostrar todas locações">Todas</a><a href="?'.http_build_query($get).'#results" class="link';
    if ($filter === 1) echo '_active';
    $get['filter'] = 2;
    echo '" title="Mostrar somente locações vigentes">Vigentes</a><a href="?'.http_build_query($get).'#results" class="link';
    if ($filter === 2) echo '_active';
    echo '" title="Mostrar somente locações encerradas">Encerradas</a></div>';

    if (!isset($_GET['action']) || $_GET['action'] != 'search') {$locacoes = getAllLocacoes($page, $filter); $results = getCountAllLocacoes($filter);} else
    if ($_GET['method'] == 0) {$locacoes = getLocacoesByImovel($_GET['value'], $page, $filter); $results = getCountLocacoesByImovel($_GET['value'], $filter);} else
    if ($_GET['method'] == 2) {$cpf = preg_replace('/[^0-9]/', '', $_GET['value']); $locacoes = getLocacoesByLocatario($cpf, $page, $filter); $results = getCountLocacoesByLocatario($cpf, $filter);}
    else $locacoes = false;
    
    if (!$locacoes) echo "Nenhuma locação encontrada."; else {

        echo '<span class="title1">Locações encontradas: </span>'.$results.'<hr>';
        
        foreach ($locacoes as $l) {

            $imovel = getImovel($l[2]);
            $locador = getClienteById($imovel[2]);
            $locatario = getClienteById($l[1]);

            echo '  <div class="box_locacao"> 
                        <span class="title1">Imóvel: </span><a href=".?action=search&method=0&value='.$l[2].'#results" title="Buscar imóvel">'.$l[2].'</a><span style="float: right;" class="title1">'.$imovel[3].'</span><hr>
                        <span class="title1">Locador: </span><a href="clientes.php?action=search&method=0&value='.$locador[0].'#results" title="Buscar locador">'.$locador[2].'</a><span style="float: right;"><span class="title1">CPF: </span>'.formatCPF($locador[1]).'</span><br>
                        <span class="title1">Locatário: </span><a href="clientes.php?action=search&method=0&value='.$locatario[0].'#results" title="Buscar locatário">'.$locatario[2].'</a><span style="float: right;"><span class="title1">CPF: </span>'.formatCPF($locatario[1]).'</span><hr>
                        <span class="title1">Período: </span>'.formatDate($l[3]).' ~ ';
                        
            if ($l[4] == "0000-00-00") echo '<b style="color: rgb(0, 200, 0);">VIGENTE</b><a onclick="confirmBox(\'Encerrar locação?\', \'Você realmente deseja encerrar esta locação?\', \'?action=encerrar&id='.$l[0].'\');" style="float: right;" title="Encerrar locação">Encerrar locação</a>';
            else echo formatDate($l[4]).'<b style="float: right;">ENCERRADA</b>';

            echo '  </div>';

        }

    }

?>

                    <hr style="margin-top: 30px;">
                    
<?php

    $get = $_GET;
    
    echo '<div style="display: inline-block; width: 150px;">';

    if ($page > 1) {$get['page'] = $page - 1; echo '<a href="?'.http_build_query($get).'#results">« Anterior</a>';}
                            
    echo '</div>Página '.$page.'<div style="display: inline-block; width: 150px;">';
                            
    if ($page * $per_page < $results) {$get['page'] = $page + 1; echo '<a href="?'.http_build_query($get).'#results">Próxima »</a>';}
    
    echo '</div>';

?>     

                </div>
            </div>
        </div>


<?php printFooter(); ?>

    </body>

</html>