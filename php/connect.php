<?php

    function db_fail() {
        exit('Falha ao conectar-se com o banco de dados.<br>Tente alterar as configuracoes relacionadas com MySQL no arquivo "php/config.php".');
    }

    $num_tables = 4;
    $db = new mysqli($db_host, $db_username, $db_password, $db_port);

    if (!$db) db_fail();

    if (!$db->select_db($db_name)) {
        if(!$db->query("CREATE DATABASE `$db_name`")) db_fail(); 
        $db->select_db($db_name);

        $db->query("CREATE TABLE `clientes` (`id` INT NOT NULL AUTO_INCREMENT, `cpf` VARCHAR(16) NOT NULL, `nome` VARCHAR(128) NOT NULL, `email` VARCHAR(128) NOT NULL, `telefone` BIGINT NOT NULL, PRIMARY KEY(`id`)) ENGINE = InnoDB");
        $db->query("CREATE TABLE `imoveis` (`id` INT NOT NULL AUTO_INCREMENT, `tipo` TINYINT(4) NOT NULL, `locador` INT NOT NULL, `endereco` VARCHAR(128) NOT NULL, `bairro` VARCHAR(128) NOT NULL, `preco` INT NOT NULL, `quartos` INT NOT NULL, `vagas` INT NOT NULL, `area` INT NOT NULL, `alugado` BOOLEAN NOT NULL, PRIMARY KEY(`id`)) ENGINE = InnoDB");
        $db->query("CREATE TABLE `locacoes` (`id` INT NOT NULL AUTO_INCREMENT, `locatario` INT NOT NULL, `imovel` INT NOT NULL, `inicio` DATE NOT NULL, `fim` DATE NOT NULL, PRIMARY KEY(`id`)) ENGINE = InnoDB");
        $db->query("CREATE TABLE `corretores` (`id` INT NOT NULL AUTO_INCREMENT, `usuario` VARCHAR(128) NOT NULL, `senha` VARCHAR(128) NOT NULL, `nome` VARCHAR(128) NOT NULL, `creci` INT NOT NULL, `email` VARCHAR(128) NOT NULL, `telefone` BIGINT NOT NULL, PRIMARY KEY(`id`)) ENGINE = InnoDB");
    }

    if ($db->query("SHOW TABLES")->num_rows != $num_tables) db_fail();

    function getBairros() {
        global $db;
        
        if (!($query = $db->query("SELECT DISTINCT `bairro` FROM `imoveis`"))) return [];
        
        $result = $query->fetch_all();
        $bairros = [];
        
        foreach ($result as $r) {
            array_push($bairros, $r[0]);
        }
        
        return $bairros;
    }
    
    // clientes

    function getCliente($cpf) {
        global $db;

        $cpf = $db->escape_string($cpf);

        $query = $db->query("SELECT * FROM `clientes` WHERE `cpf` = $cpf");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }

    function getClienteById($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("SELECT * FROM `clientes` WHERE `id` = $id");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }

    function getClientesByName($name, $page = 0) {
        global $db, $per_page;

        $name = $db->escape_string($name);
        $index = ($page - 1) * $per_page;

        $query = $db->query("SELECT * FROM `clientes` WHERE `nome` LIKE '%$name%'  ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }

    function getCountClientesByName($name) {
        global $db;

        $name = $db->escape_string($name);

        $query = $db->query("SELECT COUNT(*) FROM `clientes` WHERE `nome` LIKE '%$name%'");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function getAllClientes($page = 0) {
        global $db, $per_page;
        
        $index = ($page - 1) * $per_page;

        $query = $db->query("SELECT * FROM `clientes` ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }
    
    function getCountAllClientes() {
        global $db;

        $query = $db->query("SELECT COUNT(*) FROM `clientes`");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function addCliente($cpf, $name, $email, $phone) {
        global $db;

        $cpf = $db->escape_string($cpf);
        $name = $db->escape_string($name);
        $email = $db->escape_string($email);
        $phone = $db->escape_string($phone);

        $query = $db->query("INSERT INTO `clientes` VALUES (NULL, '$cpf', '$name', '$email', $phone)");
        return $query !== false;
    }

    function removeCliente($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("DELETE FROM `clientes` WHERE `id` = $id");
        return $query !== false;
    }
    
    // imoveis

    function getImovel($id, $filter = 0) {
        global $db;

        $id = intval($id);
        
        $where = "";
        
        if ($filter === 1) $where = "AND `alugado` = 0"; else
        if ($filter === 2) $where = "AND `alugado` = 1";
        
        $query = $db->query("SELECT * FROM `imoveis` WHERE `id` = $id $where");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }
    
    function getImoveisByLocador($cpf, $page = 0, $filter = 0) {
        global $db, $per_page;

        $locador = getCliente($cpf);
        $id = $locador[0];
        
        if (!$locador) return false;
        
        $index = ($page - 1) * $per_page;
        
        $where = "";
        
        if ($filter === 1) $where = " AND `alugado` = 0"; else
        if ($filter === 2) $where = " AND `alugado` = 1";

        $query = $db->query("SELECT * FROM `imoveis` WHERE `locador` = $id$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }
    
    function getCountImoveisByLocador($cpf, $filter = 0) {
        global $db;
        
        $locador = getCliente($cpf);
        $id = $locador[0];
        
        if (!$locador) return false;
        
        $where = "";
        
        if ($filter === 1) $where = " AND `alugado` = 0"; else
        if ($filter === 2) $where = " AND `alugado` = 1";

        $query = $db->query("SELECT COUNT(*) FROM `imoveis` WHERE `locador` = $id$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function getAllImoveis($page = 0, $filter = 0) {
        global $db, $per_page;
        
        $index = ($page-1) * $per_page;
        $where = "";
        
        if ($filter === 1) $where = " WHERE `alugado` = 0"; else
        if ($filter === 2) $where = " WHERE `alugado` = 1";

        $query = $db->query("SELECT * FROM `imoveis`$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }
    
    function getCountAllImoveis($filter = 0) {
        global $db;
        
        $where = "";
        
        if ($filter === 1) $where = " WHERE `alugado` = 0"; else
        if ($filter === 2) $where = " WHERE `alugado` = 1";

        $query = $db->query("SELECT COUNT(*) FROM `imoveis`$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }
    
    function getSearchImoveis($tipo, $bairro, $preco, $quartos, $vagas, $area, $page = 0, $filter = 0) {
        global $db, $per_page;
        
        $index = ($page-1) * $per_page;
        
        $quartos = intval($quartos);
        $vagas = intval($vagas);
        
        $where = " WHERE `quartos` >= $quartos AND `vagas` >= $vagas";
        
        $tipo = intval($tipo);
        if ($tipo > 0) $where .= " AND `tipo` = $tipo";
        
        $bairro = $db->escape_string($bairro);
        if (!empty($bairro)) $where .= " AND `bairro` = '$bairro'";
        
        if (!empty($preco)) {
            $precos = split("~", $preco);
            $preco_min = intval($precos[0]);
            $preco_max = intval($precos[1]);
            
            if ($preco_min > 0) $where .= " AND `preco` >= $preco_min";
            if ($preco_max > 0) $where .= " AND `preco` <= $preco_max";
        }

        if (!empty($area)) {
            $areas = split("~", $area);
            $area_min = intval($areas[0]);
            $area_max = intval($areas[1]);
            
            if ($area_min > 0) $where .= " AND `area` >= $area_min";
            if ($area_max > 0) $where .= " AND `area` <= $area_max";
        }
        
        if ($filter === 1) $where .= " AND `alugado` = 0"; else
        if ($filter === 2) $where .= " AND `alugado` = 1";
       
        $query = $db->query("SELECT * FROM `imoveis`$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }
    
    function getCountSearchImoveis($tipo, $bairro, $preco, $quartos, $vagas, $area, $filter = 0) {
        global $db;
        
        $quartos = intval($quartos);
        $vagas = intval($vagas);
        
        $where = " WHERE `quartos` >= $quartos AND `vagas` >= $vagas";
        
        $tipo = intval($tipo);
        if ($tipo > 0) $where .= " AND `tipo` = $tipo";
        
        $bairro = $db->escape_string($bairro);
        if (!empty($bairro)) $where .= " AND `bairro` = '$bairro'";
        
        if (!empty($preco)) {
            $precos = split("~", $preco);
            $preco_min = intval($precos[0]);
            $preco_max = intval($precos[1]);
            
            if ($preco_min > 0) $where .= " AND `preco` >= $preco_min";
            if ($preco_max > 0) $where .= " AND `preco` <= $preco_max";
        }

        if (!empty($area)) {
            $areas = split("~", $area);
            $area_min = intval($areas[0]);
            $area_max = intval($areas[1]);
            
            if ($area_min > 0) $where .= " AND `area` >= $area_min";
            if ($area_max > 0) $where .= " AND `area` <= $area_max";
        }
        
        if ($filter === 1) $where .= " AND `alugado` = 0"; else
        if ($filter === 2) $where .= " AND `alugado` = 1";
       
        $query = $db->query("SELECT COUNT(*) FROM `imoveis`$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function addImovel($tipo, $cliente, $endereco, $bairro, $preco, $quartos, $vagas, $area) {
        global $db;

        $tipo = intval($tipo);
        $endereco = $db->escape_string($endereco);
        $bairro = $db->escape_string($bairro);
        $preco = intval($preco);
        $quartos = intval($quartos);
        $vagas = intval($vagas);
        $area = intval($area);

        $query = $db->query("INSERT INTO `imoveis` VALUES (NULL, $tipo, $cliente, '$endereco', '$bairro', $preco, $quartos, $vagas, $area, 0)");
        return $query !== false;
    }

    function removeImovel($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("DELETE FROM `imoveis` WHERE `id` = $id");
        return $query !== false;
    }

    function setImovelAlugado($id, $bool) {
        global $db;

        $id = intval($id);

        $query = $db->query("UPDATE `imoveis` SET `alugado` = $bool WHERE `id` = $id");
        return $query !== false;
    }
    
    function reajustarImovel($id, $value) {
        global $db;

        $id = intval($id);
        $value = intval($value);

        $query = $db->query("UPDATE `imoveis` SET `preco` = $value WHERE `id` = $id");
        return $query !== false;
    }

    // locacoes

    function getLocacao($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("SELECT * FROM `locacoes` WHERE `id` = $id");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }
    
    function getLocacoesByLocatario($cpf, $page = 0, $filter = 0) {
        global $db, $per_page;
        
        $index = ($page-1) * $per_page;
        $cpf = $db->escape_string($cpf);
        $locatario = getCliente($cpf);
        $id = $locatario[0];
        
        if (!$locatario) return false;
        
        $where = "";
        
        if ($filter === 1) $where = " AND `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " AND `fim` != '0000-00-00'";
        
        $query = $db->query("SELECT * FROM `locacoes` WHERE `locatario` = $id$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();          
    }
    
    function getCountLocacoesByLocatario($cpf, $filter = 0) {
        global $db;

        $cpf = $db->escape_string($cpf);
        $locatario = getCliente($cpf);
        $id = $locatario[0];
        
        if (!$locatario) return false;
        
        $where = "";
        
        if ($filter === 1) $where = " AND `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " AND `fim` != '0000-00-00'";

        $query = $db->query("SELECT COUNT(*) FROM `locacoes` WHERE `locatario` = $id$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }
    
    function getLocacoesByImovel($id, $page = 0, $filter = 0) {
        global $db, $per_page;
        
        $index = ($page-1) * $per_page;
        $id = intval($id);
        $where = "";
        
        if ($filter === 1) $where = " AND `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " AND `fim` != '0000-00-00'";
        
        $query = $db->query("SELECT * FROM `locacoes` WHERE `imovel` = $id$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();          
    }
    
    function getCountLocacoesByImovel($id, $filter = 0) {
        global $db;
        
        $id = intval($id);
        $where = "";
        
        if ($filter === 1) $where = " AND `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " AND `fim` != '0000-00-00'";

        $query = $db->query("SELECT COUNT(*) FROM `locacoes` WHERE `imovel` = $id$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function getAllLocacoes($page = 0, $filter = 0) {
        global $db, $per_page;
        
        $index = ($page-1) * $per_page;
        $where = "";
        
        if ($filter === 1) $where = " WHERE `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " WHERE `fim` != '0000-00-00'";

        $query = $db->query("SELECT * FROM `locacoes`$where ORDER BY `id` DESC". ($page > 0 ? " LIMIT $index, $per_page" : ""));
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }
    
    function getCountAllLocacoes($filter = 0) {
        global $db;
        
        $where = "";
        
        if ($filter === 1) $where = " WHERE `fim` = '0000-00-00'"; else
        if ($filter === 2) $where = " WHERE `fim` != '0000-00-00'";

        $query = $db->query("SELECT COUNT(*) FROM `locacoes`$where");
        if (!$query) return false; else return $query->fetch_row()[0];
    }

    function addLocacao($id_locatario, $id_imovel) {
        global $db;

        $id_locatario = intval($id_locatario);
        $id_imovel = intval($id_imovel);

        $query = $db->query("INSERT INTO `locacoes` VALUES (NULL, $id_locatario, $id_imovel, '".date("Y-m-d")."', '0000-00-00')");
       
        return $query;
    }

    function encerrarLocacao($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("UPDATE `locacoes` SET `fim` = '".date("Y-m-d")."' WHERE `id` = $id");
        return $query !== false;
    }

    function removeLocacoesByImovel($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("DELETE FROM `locacoes` WHERE `imovel` = $id");
        return $query !== false;
    }

    function removeLocacao($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("DELETE FROM `locacoes` WHERE `id` = $id");
        return $query !== false;
    }


    // corretores

    function getCorretor($username) {
        global $db, $admin_username, $admin_password, $name, $creci, $phone;

        if ($username === $admin_username) return [0, $admin_username, $admin_password, $name, $creci, "", $phone];

        $username = $db->escape_string($username);

        $query = $db->query("SELECT * FROM `corretores` WHERE BINARY `usuario` = '$username'");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }

    function getCorretorById($id) {
        global $db, $admin_username, $admin_password, $name, $creci, $phone;

        if ($id == 0) return [0, $admin_username, $admin_password, $name, $creci, "", $phone];

        $id = intval($id);

        $query = $db->query("SELECT * FROM `corretores` WHERE `id` = $id");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_row();
    }

    function getAllCorretores() {
        global $db;

        $query = $db->query("SELECT * FROM `corretores`");
        if (!$query || $query->num_rows < 1) return false; else return $query->fetch_all();
    }

    function addCorretor($user, $password, $name, $creci, $email, $phone) {
        global $db;

        $user = $db->escape_string($user);
        $password = $db->escape_string($password);
        $name = $db->escape_string($name);
        $email = $db->escape_string($email);
        $phone = $db->escape_string($phone);
        $creci = intval($creci);

        $query = $db->query("INSERT INTO `corretores` VALUES (NULL, '$user', '$password', '$name', $creci, '$email', $phone)");
        return $query !== false;
    }

    function removeCorretor($id) {
        global $db;

        $id = intval($id);

        $query = $db->query("DELETE FROM `corretores` WHERE `id` = $id");
        return $query !== false;
    }
