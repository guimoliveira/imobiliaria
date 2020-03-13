<?php

// Informações da imobiliária

    $name = "Imobiliária"; // Nome da imobiliária
    $creci = "12.345"; // CRECI da imobiliária
    $addr = "Av. das Imobiliárias, 1250 - Centro - Gravataí"; // Endereço da imobiliária
    $phone = "(51) 8765-1234"; // Telefone da imobiliária
    $about = 'Para adicionar informações aqui, edite o conteúdo da variável "$about" no arquivo "php/config.php".'; // Texto sobre a imobiliária, localizado no menu "Sobre"

    
// Tipos de imóveis    
    
    $types = [ "Apartamento", 
               "Box-Garagem", 
               "Casa", 
               "Cobertura", 
               "Kitnet", 
               "Loja", 
               "Pavilhão", 
               "Prédio", 
               "Sala", 
               "Sobrado", 
               "Sítio",  
               "Terreno", 
               "Área" ]; 

    
// Configurações do banco de dados MySQL (null = padrão)
    
    $db_name = "imobiliaria_db"; // Nome do banco de dados
    $db_host = "localhost"; // Host do MySQL
    $db_username = "root"; // Usuário do MySQL
    $db_password = ""; // Senha do MySQL
    $db_port = null; // Porta do MySQL
    
    
// Administração
    
    $admin_username = "admin"; // Usuário administrador
    $admin_password = "admin"; // Senha do administrador
  
    
// Registros por página  
    
    $per_page = 5; 
    
    
// Fontes
    
    $title_font = "'Anton', Impact, Arial"; // Fonte do título
    $body_font = "Arial, Helvetica, sans-serif"; // Fonte da página
    
    
// Cores
    
    $background_color = "#dedede"; // Cor de fundo
    $shadow_color = "lightgrey"; // Cor das sombras e bordas
    
    $box_color = "white"; // Cor das caixas
    $box_hover_color = "#f2f2f2"; // Cor ao passar por uma caixa
    
    $hyperlink_color = "rgb(76, 184, 226)"; // Cor dos hyperlinks
    $hyperlink_hover_color = "rgb(91, 166, 196)"; // Cor ao passar por hyperlinks
    $hyperlink_active_color = "rgb(47, 117, 145)"; // Cor ao clicar em um hyperlink
    
    $header_color = "rgb(57, 141, 82)"; // Cor da barra superior
    $footer_color = "white"; // Cor da barra inferior
    
    $menu_bar_color = "white"; // Cor do menu
    $menu_button_color = "white"; // Cor dos botões do menu
    $menu_button_hover_color = "#ececec"; // Cor ao passar por um botão do menu
    
    $title_color = "white"; // Cor de titulo
    $title1_color = "black"; // Cor de titulo 1
    $title2_color = "rgb(54, 116, 72)"; // Cor de titulo 2
    
    $tab_color = "black"; // Cor da fonte de uma aba
    $tab_hover_color = "green"; // Cor ao passar por um aba
    $tab_active_color = "green"; // Cor ao clicar em uma aba
   
    $box_button_color = "darkgreen"; // Cor do botão da caixa
    $box_button_hover_color = "green"; // Cor ao passar por um botão de uma caixa
    $box_button_active_color = "#014301"; // Cor ao clicar por um botão de uma caixa