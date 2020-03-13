<?php

    error_reporting(0);

    require("php/config.php");
    require("php/connect.php");
    require("php/methods.php");
    
    startSession();

    if ($logged && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'cadastrar':
                $add_imovel = true;
                $error = false;

                if (isset($_POST['tipo']) && !empty($_POST['cpf']) && !empty($_POST['endereco']) && !empty($_POST['bairro']) && !empty($_POST['preco']) && isset($_POST['quartos']) && isset($_POST['vagas']) && !empty($_POST['area'])) {
                    
                    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);

                    if ($_POST['tipo'] < 1 || $_POST['tipo'] > count($types)) $error = "Tipo inválido"; else
                    if (strlen($cpf) != 11) $error = "CPF inválido."; else 
                    if (!is_numeric($_POST['preco']) || $_POST['preco'] <= 0) $error = "Preço inválido."; else
                    if (!is_numeric($_POST['quartos']) || $_POST['quartos'] < 0) $error = "Quantidade de quartos inválida."; else
                    if (!is_numeric($_POST['vagas']) || $_POST['vagas'] < 0) $error = "Quantidade de vagas inválida."; else
                    if (!is_numeric($_POST['area']) || $_POST['area'] < 0) $error = "Área inválida."; else 
                    if (!($cliente = getCliente($cpf))) $error = "CPF não registrado, registre-o primeiro, acessando o menu Clientes."; else
                    if (!addImovel($_POST['tipo'], $cliente[0], $_POST['endereco'], $_POST['bairro'], $_POST['preco'], $_POST['quartos'], $_POST['vagas'], $_POST['area'])) $error = "Falha no banco de dados."; 
                    else {
                        $id = $db->insert_id;
                        move_uploaded_file($_FILES['photo']['tmp_name'], "imgs/imoveis/$id");

                        header("Location: ?");
                    }

                } else {
                    $error = "Preencha todos os campos.";
                }
                break;
            case "remover":
                
                if (isset($_GET['id'])) {
                    removeImovel($_GET['id']); 
                    removeLocacoesByImovel($_GET['id']);
                                        
                    header("Location: ?");
                }

                break;
            case 'reajustar':
                
                if (isset($_GET['id']) && isset($_GET['value'])) {
                    reajustarImovel($_GET['id'], $_GET['value']);
                                        
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

        <title><?php echo $name; ?></title>

        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
        <link href="css/style.php" rel="stylesheet">
        
        <link href="imgs/favicon.png" rel="shortcut icon">

        <script type="text/javascript" src="js/js.js"></script>
    </head>

    <body onload="searchMethodChanged();">

<?php printHeader(0); ?>

        <div class="center" style="margin-top: 25px; margin-bottom: 25px; text-align: center;">
            <div class="division" style="width: 300px;">
                <div class="box">
                    <div class="title_box">Busca<?php if (!$logged) echo ' por código';?></div>
                    <form method="get" action="#results">
                        <input type="hidden" name="action" value="search">
                        <select name="method" class="input" id="search_method" onchange="searchMethodChanged();" <?php if (!$logged) echo 'style="display: none;"'; ?>>
                            <option selected value="0" <?php if (isset($_GET['method']) && $_GET['method'] == 0) echo "selected"; ?>>Por código</option>
                            <option value="1" <?php if (isset($_GET['method']) && $_GET['method'] == 1) echo "selected"; ?>>Por locador</option>
                        </select>
                        <input name="value" type="text" placeholder="" id="search_input" class="input" value="<?php if (isset($_GET['value'])) echo $_GET['value']; ?>" required>
                       
<?php if (isset($_GET['action']) && $_GET['action'] == 'search') echo '<a href="?#results" title="Cancelar busca">Cancelar busca</a><br>'; ?>
                        
                        <input type="submit" class="button" value="Buscar">
                    </form>
                </div>

                <div class="box">
                    <div class="title_box">Busca avançada</div>
                    <form method="get" action="#results">
                        <input type="hidden" name="action" value="advanced_search">
                        
                        <select name="tipo" class="input">
                            <option value="0">Tipo</option>
<?php 

    foreach ($types as $i => $t) {
        echo '<option value="'.($i+1).'"';
        if (isset($_GET['tipo']) && $_GET['tipo'] == $i+1) echo ' selected';
        echo '>'.$t.'</option>';
    }

?>
                        </select>
                        <select name="bairro" class="input">
                            <option value="">Bairro</option>
                            
<?php

    $bairros = getBairros();
    
    foreach ($bairros as $b) {
        echo '<option value="'.$b.'"';
        if (isset($_GET['bairro']) && $_GET['bairro'] == $b) echo ' selected';
        echo '>'.$b.'</option>';
    }

?>
                              
                        </select>
                        <select name="preco" class="input">
                            <option value="">Preço</option>
                            <option value="~400"<?php if (isset($_GET['preco']) && $_GET['preco'] == '~400') echo ' selected';?>>Até 400</option>
                            <option value="400~600"<?php if (isset($_GET['preco']) && $_GET['preco'] == '400~600') echo ' selected';?>>De 400 até 600</option>
                            <option value="600~800"<?php if (isset($_GET['preco']) && $_GET['preco'] == '600~800') echo ' selected';?>>De 600 até 800</option>
                            <option value="800~1000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '800~1000') echo ' selected';?>>De 800 até 1.000</option>
                            <option value="1000~2000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '1000~2000') echo ' selected';?>>De 1.000 até 2.000</option>
                            <option value="2000~4000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '2000~4000') echo ' selected';?>>De 2.000 até 4.000</option>
                            <option value="4000~8000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '4000~8000') echo ' selected';?>>De 4.000 até 8.000</option>
                            <option value="8000~10000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '8000~10000') echo ' selected';?>>De 8.000 até 10.000</option>
                            <option value="10000~20000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '10000~20000') echo ' selected';?>>De 10.000 até 20.000</option>
                            <option value="20000~40000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '20000~40000') echo ' selected';?>>De 20.000 até 40.000</option>
                            <option value="40000~80000"<?php if (isset($_GET['preco']) && $_GET['preco'] == '40000~80000') echo ' selected';?>>De 40.000 até 80.000</option>
                            <option value="80000~"<?php if (isset($_GET['preco']) && $_GET['preco'] == '80000~') echo ' selected';?>>Acima de 80.000</option>
                        </select>
                        <select name="quartos" class="input">
                            <option value="">Quartos</option>
                            <option value="1"<?php if (isset($_GET['quartos']) && $_GET['quartos'] == 1) echo ' selected';?>>1+</option>
                            <option value="2"<?php if (isset($_GET['quartos']) && $_GET['quartos'] == 2) echo ' selected';?>>2+</option>
                            <option value="3"<?php if (isset($_GET['quartos']) && $_GET['quartos'] == 3) echo ' selected';?>>3+</option>
                            <option value="4"<?php if (isset($_GET['quartos']) && $_GET['quartos'] == 4) echo ' selected';?>>4+</option>
                            <option value="5"<?php if (isset($_GET['quartos']) && $_GET['quartos'] == 5) echo ' selected';?>>5+</option>
                        </select>
                        <select name="vagas" class="input">
                            <option value="">Vagas</option>
                            <option value="1"<?php if (isset($_GET['vagas']) && $_GET['vagas'] == 1) echo ' selected';?>>1+</option>
                            <option value="2"<?php if (isset($_GET['vagas']) && $_GET['vagas'] == 2) echo ' selected';?>>2+</option>
                            <option value="3"<?php if (isset($_GET['vagas']) && $_GET['vagas'] == 3) echo ' selected';?>>3+</option>
                            <option value="4"<?php if (isset($_GET['vagas']) && $_GET['vagas'] == 4) echo ' selected';?>>4+</option>
                            <option value="5"<?php if (isset($_GET['vagas']) && $_GET['vagas'] == 5) echo ' selected';?>>5+</option>
                        </select>
                        <select name="area" class="input">
                            <option value="">Área</option>
                            <option value="~40"<?php if (isset($_GET['area']) && $_GET['area'] == '~40') echo ' selected';?>>Até 40 m²</option>
                            <option value="40~60"<?php if (isset($_GET['area']) && $_GET['area'] == '40~60') echo ' selected';?>>De 40 até 60 m²</option>
                            <option value="60~80"<?php if (isset($_GET['area']) && $_GET['area'] == '60~80') echo ' selected';?>>De 60 até 80 m²</option>
                            <option value="80~100"<?php if (isset($_GET['area']) && $_GET['area'] == '80~100') echo ' selected';?>>De 80 até 100 m²</option>
                            <option value="100~200"<?php if (isset($_GET['area']) && $_GET['area'] == '100~200') echo ' selected';?>>De 100 até 200 m²</option>
                            <option value="200~400"<?php if (isset($_GET['area']) && $_GET['area'] == '200~400') echo ' selected';?>>De 200 até 400 m²</option>
                            <option value="400~"<?php if (isset($_GET['area']) && $_GET['area'] == '400~') echo ' selected';?>>Acima de 400 m²</option>
                        </select>

<?php if (isset($_GET['action']) && $_GET['action'] == 'advanced_search') echo '<a href="?#results" title="Cancelar busca">Cancelar busca</a><br>'; ?>

                        <input type="submit" class="button" value="Buscar">
                    </form>
                </div>
            </div><div class="division" style="width: 600px; margin-left: 20px;">
<?php

    if ($logged) {

        echo '
                    <div class="box">
                        <div class="title_box">Cadastrar imóvel</div>

                        <form method="post" action="?action=cadastrar" enctype="multipart/form-data">

                            Foto do imóvel: <input type="file" name="photo" accept="image/*" style="margin-left: 10px; font-size: 12pt;" title="Foto do imóvel"><br><br>

                            <select name="tipo" class="input">
                                <option value="0">Tipo</option>';

        foreach ($types as $i => $t) {
            echo '<option value="'.($i+1).'"';
            
            if (isset($add_imovel) && $_POST['tipo'] - 1 == $i) echo ' selected';

            echo '>'.$t.'</option>';
        }    

        echo '              </select>
                            <input type="text" name="cpf" class="input" placeholder="CPF do locador" maxlength="14"'; if (isset($add_imovel)) echo 'value="'.$_POST['cpf'].'" '; echo 'required>
                            <input type="text" name="endereco" class="input" placeholder="Endereço" maxlength="128"'; if (isset($add_imovel)) echo 'value="'.$_POST['endereco'].'" '; echo 'required>
                            <input type="text" name="bairro" class="input" placeholder="Bairro" maxlength="128"'; if (isset($add_imovel)) echo 'value="'.$_POST['bairro'].'" '; echo 'required>
                            <input type="number" name="preco" class="input" placeholder="Preço por mês" min="1" max="99999"'; if (isset($add_imovel)) echo 'value="'.$_POST['preco'].'" '; echo 'required>
                            <input type="number" name="quartos" class="input" placeholder="Quartos" min="0" max="99"'; if (isset($add_imovel)) echo 'value="'.$_POST['quartos'].'" '; echo 'required>
                            <input type="number" name="vagas" class="input" placeholder="Vagas" min="0" max="99"'; if (isset($add_imovel)) echo 'value="'.$_POST['vagas'].'" '; echo 'required>
                            <input type="number" name="area" class="input" placeholder="Área em m²" min="10" max="99999"'; if (isset($add_imovel)) echo 'value="'.$_POST['area'].'" '; echo 'required>';

        if (isset($add_imovel) && $error !== false) {
            echo "<div class=\"form_error\">$error</div>";
        }

        echo '             <input type="submit" class="button" value="Cadastrar">

                        </form>
                    </div>
        ';
    }

?>
                <div class="box" id="results">
                     
                    <div class="title_box">

<?php

    $results = 0;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $filter = $logged ? (isset($_GET['filter']) ? intval($_GET['filter']) : 0) : 1;

    if ($logged) {
        $get = $_GET;
        $get['page'] = 1;
        
        $get['filter'] = 0;
        echo '<a href="?'.http_build_query($get).'#results" class="link';
        if ($filter === 0) echo '_active';
        $get['filter'] = 1;
        echo '" title="Mostrar todos imóveis">Todos</a><a href="?'.http_build_query($get).'#results" class="link';
        if ($filter === 1) echo '_active';
        $get['filter'] = 2;
        echo '" title="Mostrar somente imóveis disponíveis">Disponíveis</a><a href="?'.http_build_query($get).'#results" class="link';
        if ($filter === 2) echo '_active';
        echo '" title="Mostrar somente imóveis alugados">Alugados</a></div>';
    } else {
        echo 'Imóveis para alugar</div>';
        echo 'Para cadastrar o seu imóvel ou alugar um disponível,<br>entre em contato com um de nossos <a href="corretores.php" title="Corretores">corretores</a>.<hr>';
    }
   
    if (!isset($_GET['action']) || ($_GET['action'] != 'search' && $_GET['action'] != 'advanced_search')) {$imoveis = getAllImoveis($page, $filter); $results = getCountAllImoveis($filter);} else
    if ($_GET['action'] == 'search' && $_GET['method'] == 0 && ($imovel = getImovel($_GET['value'], $filter))) {$imoveis = [$imovel]; $results = $filter == 0 ? 1 : intval(($filter - 1) == $imovel[9]);} else
    if ($_GET['action'] == 'search' && $_GET['method'] == 1 && $logged) {$cpf = preg_replace('/[^0-9]/', '', $_GET['value']); $imoveis = getImoveisByLocador($cpf, $page, $filter); $results = getCountImoveisByLocador($cpf, $filter);} else
    if ($_GET['action'] == 'advanced_search') {$imoveis = getSearchImoveis($_GET['tipo'], $_GET['bairro'], $_GET['preco'], $_GET['quartos'], $_GET['vagas'], $_GET['area'], $page, $filter); $results = getCountSearchImoveis($_GET['tipo'], $_GET['bairro'], $_GET['preco'], $_GET['quartos'], $_GET['vagas'], $_GET['area'], $filter);}
    else $imoveis = false;
    
    if (!$imoveis || $results == 0) echo 'Nenhum imóvel encontrado.'; else {

        echo '
            <span class="title1">Imóveis encontrados: </span>'.$results.'<hr>';
        
        foreach ($imoveis as $i) {
            echo '  <div class="box_imovel">
                        <a href="imgs/imoveis/'.$i[0].'" target="_blank" title="Clique para aumentar">
                            <div class="img_imovel">
                                <div style="background-image: url(\'imgs/imoveis/'.$i[0].'\'); background-size: cover; background-position: center; width: 100%; height: 100%;"></div>
                            </div>
                        </a>
                    
                        <div class="info_imovel">
                            <span class="title_imovel">'.$types[$i[1]-1].' - '.$i[4].'</span><br>';
                            
            if ($logged) {
                echo $i[3];
                
                if ($i[9]) echo '<b style="float: right;">ALUGADO</b>'; else echo '<b style="float: right; color: rgb(0, 200, 0);">DISPONÍVEL</b>';
            }
                            
            echo '<br><hr style="margin-bottom: 14px;"><span class="title1">Quartos: </span>'.$i[6].'&nbsp;&nbsp;&nbsp;<span class="title1">Vagas: </span>'.$i[7].'&nbsp;&nbsp;&nbsp;<span class="title1">Área: </span>'.$i[8].'m²
            ';
            if ($logged) {
                echo '
                            <div class="buttons_imovel">
                                <a onclick="promptBox(\'Reajustar preço\', \'Novo preço\', \'?action=reajustar&id='.$i[0].'\');" title="Reajustar preço">Reajustar preço</a> - <a onclick="confirmBox(\'Remover imóvel?\', \'Todos os registros relacionados a este imóvel também serão removidos.\', \'?action=remover&id='.$i[0].'\');" title="Remover imóvel">Remover</a> - <a href="clientes.php?action=search&method=0&value='.$i[2].'#results" title="Buscar locador deste imóvel">Locador</a> - <a href="locacoes.php?action=search&method=0&value='.$i[0].'#results" title="Buscar locações deste imóvel">Locações</a>';
                                
                if (!$i[9]) echo '<a href="locacoes.php?action=alugar&id='.$i[0].'" style="float: right;">Alugar</a>';

                echo '      </div>';
            }
            echo '          <div class="preco_imovel" style="position: absolute; right: 10px; bottom: ';

            if ($logged) echo '48px;'; else echo '10px';
                            
            echo '">
                                R$'. number_format($i[5], 2, ',', '.').'
                            </div>
                        </div>

                        <div class="codigo_imovel">
                            Código do imóvel: '.$i[0].'
                        </div>

                    </div>';
        }

    }

?>
                    <hr style="margin-top: 30px;">
 
<?php
    
    $get = $_GET;

    echo '<div style="display: inline-block; width: 150px;">';

    if ($page > 1) {$get['page'] = $page - 1; echo '<a href="?'.http_build_query($get).'#results" title="Página anterior">« Anterior</a>';}
                            
    echo '</div>Página '.$page.'<div style="display: inline-block; width: 150px;">';
                            
    if ($page * $per_page < $results) {$get['page'] = $page + 1; echo '<a href="?'.http_build_query($get).'#results" title="Próxima página">Próxima »</a>';}
    
    echo '</div>';

?>                            
                            
                </div>
            </div>
        </div>

<?php printFooter(); ?>
                    
    </body>

</html>