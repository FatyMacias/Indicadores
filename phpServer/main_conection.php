<?php


define('HOST', 'localhost:3306');
define('USER', 'root');
define('BD', 'indicadores');
define('PASS', '');

class Database

{

    public static function Conectar()

    {

        $pdo =  new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8', USER, PASS);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

        return $pdo;
 
    }

}