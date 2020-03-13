<?php 

    header("Content-type: text/css; charset: UTF-8"); 
    require '../php/config.php';
    
?>

body {
    background: <?php echo $background_color; ?>;
    font-family: <?php echo $body_font; ?>;
    font-size: 12pt;
    margin: 0;
    line-height: 1.5;
}

hr {
    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}

a {
    color: <?php echo $hyperlink_color; ?>;
    text-decoration: underline;
    cursor: pointer;
}

a:hover {
    color: <?php echo $hyperlink_hover_color; ?>;
}

a:active {
    color: <?php echo $hyperlink_active_color; ?>;
    text-decoration: none;
}

.header {
    width: 100%;
    height: 170px;
    background: <?php echo $header_color; ?>;
    min-width: 950px;
}

.footer {
    padding: 20px 0;
    background: <?php echo $footer_color; ?>;
    box-shadow: 0 -.2em .2em 0 <?php echo $shadow_color; ?>;
    min-width: 950px;
}

.user_bar {
    padding: 7px 15px 7px 15px;
    border-radius: 0 0 10px 10px;
    position: absolute;
    right: 0;
    top: 0;
    color: white;
    background: rgba(0, 0, 0, 0.3);
    font-size: 11pt;
}

.bar {
    width: 100%;
    background: <?php echo $menu_bar_color; ?>;
    box-shadow: 0 .2em .2em 0 <?php echo $shadow_color; ?>;
}

.icon {
    background-image: url('../imgs/home.png');
    background-size: 100%;
    width: 150px;
    height: 125px;
    position: absolute;
    left: 0;
    bottom: 0;
}

.title {
    color: <?php echo $title_color; ?>;
    font-size: 30pt;
    font-family: <?php echo $title_font; ?>;
    position: absolute;
    left: 200px;
    bottom: 30px;
}

.title1 {
    color: <?php echo $title2_color; ?>;
    font-weight: bold;
}

.creci {
    color: <?php echo $title_color; ?>;
    font-size: 9pt;
    position: absolute;
    left: 200px;
    bottom: 17px;    
}

.center {
    position: relative;
    margin: auto;
    width: 950px;
}

.menu_button, .menu_button_active {
    width: 120px;
    color: <?php echo $title2_color; ?>;
    background: <?php echo $menu_button_color; ?>;
    line-height: 25px;
    display: inline-block;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
}

.menu_button:hover {
    background: <?php echo $menu_button_hover_color; ?>;
    color: <?php echo $title2_color; ?>;
}

.menu_button:active, .menu_button_active {
    font-weight: bold;    
}

.menu_button_active:hover {
    color: <?php echo $title2_color; ?>;
}

.box {
    border: 1px solid <?php echo $shadow_color; ?>;
    box-shadow: 0 0 .4em .2em <?php echo $shadow_color; ?>;
    background: <?php echo $box_color; ?>;  
    padding: 15px;  
    margin-bottom: 15px;
    position: relative;
}

.box_corretor {
    width: 350px;
    margin: 10px;
    display: inline-block;
    border: 1px solid <?php echo $shadow_color; ?>;
    background: <?php echo $box_color; ?>;  
    padding: 15px;  
    cursor: default;
}

.box_cliente {
    margin: 10px;
    border: 1px solid <?php echo $shadow_color; ?>;
    background: <?php echo $box_color; ?>;  
    padding: 15px; 
    text-align: left;
    cursor: default;
    font-size: 11pt;
}

.box_imovel {
    margin: 10px;
    border: 1px solid <?php echo $shadow_color; ?>;
    background: <?php echo $box_color; ?>;  
    text-align: left;
    position: relative;
    height: 150px;
    cursor: default;
}

.box_locacao {
    margin: 10px;
    border: 1px solid <?php echo $shadow_color; ?>;
    background: <?php echo $box_color; ?>;  
    padding: 15px; 
    text-align: left;
    cursor: default;
    font-size: 11pt;
}

.box_corretor:hover, .box_cliente:hover, .box_imovel:hover, .box_locacao:hover {
    background: <?php echo $box_hover_color;?>;
}

.division {
    display: inline-block;
    vertical-align: top;
}

.title_box {
    font-size: 14pt;
    padding-bottom: 15px;
    color: <?php echo $title1_color; ?>;
}

.input {
    padding: 10px 0 10px 10px;
    width: 235px;
    margin-bottom: 10px;
    box-sizing: content-box;
    border: 1px solid <?php echo $shadow_color; ?>;
    color: black;
    font-size: 10pt;
    line-height: 18px;
    height: 18px;
}

.button {
    width: 150px;
    padding: 10px;
    margin-top: 5px;
}

.form_error {
    color: red;
    font-size: 10pt;
    line-height: 1;
    margin-bottom: 10px;
}

.link, .link_active {
    cursor: pointer;
    margin: 12px;
    color: <?php echo $tab_color; ?>;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
    color: <?php echo $tab_hover_color; ?>;
}

.link:active, .link_active {
    text-decoration: none;
    color: <?php echo $tab_active_color; ?>;
}

.link_active {
    font-weight: bold;
}

.link_active:hover {
    color: <?php echo $tab_active_color; ?>;
}

.img_imovel {
    position: absolute;
    left: 0;
    top: 0;
    width: 150px;
    height: 150px;
    background-image: url('../imgs/default.png');
    background-size: 100%;
    border: 0;
    cursor: pointer;
}

.info_imovel {
    position: absolute;
    left: 150px; top: 0; right: 0; bottom: 0;
    padding: 10px;
    font-size: 10pt;
}

.codigo_imovel {
    position: absolute;
    top: 0; right: 0;
    font-size: 9pt;
    padding: 2px 10px;
    color: grey;
    background: #dedede;
    border-bottom-left-radius: 8px;
}

.title_imovel {
    font-size: 14pt;
    color: <?php echo $title1_color; ?>
}

.preco_imovel {
    font-size: 14pt;
    font-weight: bold;
}

.buttons_imovel {
    position: absolute;
    left: 10px; bottom: 12px; right: 10px;
}

#background_box {
    background: rgba(0,0,0,.7);
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: none;
}

#box {
    margin: auto;
    width: 400px;
    background: white;
    margin-top: 100px;
    padding: 15px;
    text-align: center;
}

#msg {
    margin-bottom: 15px;
}

.button_box {
    background: <?php echo $box_button_color; ?>;
    display: inline-block;
    padding: 5px 20px;
    margin: 5px;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

.button_box:hover {
    background: <?php echo $box_button_hover_color; ?>;
    color: white;
}

.button_box:active {
    background: <?php echo $box_button_active_color; ?>;
    color: white;
}