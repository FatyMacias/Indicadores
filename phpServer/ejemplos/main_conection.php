<?php


define('HOST', 'localhost');
define('USER', 'root');
define('BD', 'andys_tec');
define('PASS', '');

/*define('HOST', 'localhost');
define('USER', 'andys_tecuser');
define('BD', 'andys_tec');
define('PASS', 'Tec20192019');*/

class Database

{

    public static function Conectar()

    {

        $pdo =  new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8', USER, PASS);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

        return $pdo;
 
    }

}