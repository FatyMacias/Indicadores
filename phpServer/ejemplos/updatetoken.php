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

$action = call_user_func( "saveToken", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function saveToken($obj){
    try {
        $dbc = Database::Conectar();
        if (empty($obj->idCurso) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{
            $stmt = $dbc->prepare(
                "UPDATE `cursos` SET token='$obj->token'
                WHERE ID='$obj->idCurso'"
            );
            
            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];

            return $error;
            $this->dbc = null;

        }else{

            $response = (object) [
                        'result' => 'ok',
                        'data' => 'token'
                    ];

            return  $response;
            $this->dbc = null;
        };
    };
} catch ( PDOException $errMsg ) {
        $error = (object) [
                    'result' => 'error',
                    'data' => $errMsg
                ];
        return $error;
    }   
};