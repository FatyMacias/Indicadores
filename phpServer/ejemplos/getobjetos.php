<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require_once 'main_conection.php';

session_start();
$postdata=file_get_contents('php://input');
$obj=json_decode($postdata);

Header("Content-Type: application/json;charset=UTF-8");

/*
if(!isset($obj->data)){
    $obj->data = null;
}
*/

$action = call_user_func( "obtenerMenu", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function obtenerMenu($obj)
{
    try {
        $dbc = Database::Conectar();



                $stmt = $dbc->prepare("
                SELECT * FROM `objetos`
                order by `ID` desc");

                $stmt->execute();

    
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    
                return $data;

                $this->dbc = null;

            // PDO error handling
        
    } catch ( PDOException $errMsg ) {

        return $errMsg;
    }   
}