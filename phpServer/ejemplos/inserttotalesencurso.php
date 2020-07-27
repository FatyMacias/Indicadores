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

$action = call_user_func( "saveCurso", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function saveCurso($obj){
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
                "UPDATE `cursos` SET problemsM1='$obj->problemsM1',problemsM2='$obj->problemsM2',problemsM3='$obj->problemsM3',problemsM4='$obj->problemsM4'
                WHERE ID='$obj->idCurso'");


            if (!$stmt->execute()) {

                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];

                return $error;
                $this->dbc = null;
            }else{
                $guardado = (object) [
                    'result' => 'ok',
                    'data' => 'Guardado'
                ];
                return  $guardado;
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