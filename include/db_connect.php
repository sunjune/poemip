<?php
//数据库连接参数
$PoemDB = [
    'ip' => 'localhost',
    'port' => '3306',

    'user' => 'root',
    'pass' => '111111',

    'database' => 'poemip'
];

function get_db_connect($db_server_ip, $db_server_port, $db_login_user, $db_login_pass, $db_database_name){
    $pdo = array();
    try {
        $pdo_options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $pdo = new PDO("mysql:host=$db_server_ip;port=$db_server_port;dbname=$db_database_name", $db_login_user, $db_login_pass, $pdo_options);
    } catch (PDOException $e) {
        $pdo["error"] = $e->getMessage();
    }

    return $pdo;
}

?>