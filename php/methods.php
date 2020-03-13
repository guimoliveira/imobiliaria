<?php

    $logged = false;
    $user_id = -1;
    $username = "";
    
    $tabs = [['Imóveis', '.'], ['Clientes', 'clientes.php'], ['Locações', 'locacoes.php'], ['Corretores', 'corretores.php'], ['Sobre', 'sobre.php'], ['Login', 'login.php'], ['Logoff', 'login.php?action=logoff']];

    function startSession() {
        global $logged, $user_id, $username, $admin_username;
        
        session_start();

        if (isset($_SESSION['logged'])) $logged = $_SESSION['logged'];

        if ($logged) {
            $user_id = $_SESSION['user_id'];
            if ($user_id === 0) $username = $admin_username;
            else {
                $username = getCorretorById($user_id)[1];
            }
        } else {
            $username = "visitante";
        }
        
    }
        
    function formatDate($date) {
        return date("d/m/Y", strtotime($date));
    }

    function formatPhone($phone) {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }

        return $phone; 
    }

    function formatCPF($cpf) {
        $formatedCPF = preg_replace('/[^0-9]/', '', $cpf);
        $matches = [];
        preg_match('/^([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})$/', $formatedCPF, $matches);
        if ($matches) {
            return $matches[1].'.'.$matches[2].'.'.$matches[3].'-'.$matches[4];
        }

        return $cpf; 
    }
    
    function printHeader($tab) {
        global $tabs, $name, $creci, $username, $logged;
        
        echo '
            <div class="header">
                <div class="center" style="height: 145px;">
                    <div class="title">'.$name.'</div>
                    <div class="creci">CRECI '.$creci.'</div>
                    <div class="icon">&nbsp;</div>

                    <div class="user_bar">Bem-vindo <b>'.$username.'</b></div>
                </div>
                <div class="bar">
                    <div class="center">
                        <div style="margin-left: 170px;">';

        for ($i = 0; $i<5; $i++) {
            if (!$logged && ($i === 1 || $i === 2)) continue;

            echo '<a href="'.$tabs[$i][1].'" class="menu_button';

            if ($tab === $i) echo '_active';

            echo '" title="'.$tabs[$i][0].'">'.$tabs[$i][0].'</a>';
        }

        if ($logged) $i++;

        echo '</div>
                        <a href="'.$tabs[$i][1].'" class="menu_button';

        if ($tab === $i) echo '_active';   

        echo '" style="position: absolute; right: 0; top: 0;" title="'.$tabs[$i][0].'">'.$tabs[$i][0].'</a>
                    </div>
                </div>
            </div>
        ';
    }
    
    function printFooter() {
        global $addr, $phone;
        
        echo '
            <div class="footer">
                <div class="center">
                    <span style="margin-left: 15px;">'.$addr.'</span>
                    <span style="float: right; margin-right: 15px;">Telefone: '.$phone.'</span>
                </div>
            </div>
        
            <div id="background_box">
                <div id="box">
                    <div id="title" class="title_box"></div>

                    <div id="msg"></div>

                    <a href="" class="button_box" id="button_yes">Sim</a><div class="button_box" id="button_no" onclick="closeBox();">Não</div>

                </div>
            </div>
        ';
    }