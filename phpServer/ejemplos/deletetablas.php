<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require_once 'main_conection.php';

session_start();
$postdata=file_get_contents('php://input');
$obj=json_decode($postdata);

Header("Content-Type: application/json;charset=UTF-8");

$action = call_user_func( "delete", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function delete($obj){
    try {
        $dbc = Database::Conectar();
        if (empty($obj->nombreT) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

            $stmtDelete = $dbc->prepare("DELETE FROM `$obj->nombreT`");

            if (!$stmtDelete->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data];
                
                return $error;
                $this->dbc = null;
            }else{

                $data = (object) [
                    'result' => 'ok',
                    'data' => 'borrado'];
                
                return  $data;
                $this->dbc = null;
            };
        };
    } catch ( PDOException $errMsg ) {

        $error = (object) [
            'result' => 'error',
            'data' => $errMsg];
        
        return $error;
    };
};