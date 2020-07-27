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
        if (empty($obj->id) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

             $elemento=$obj->elemento;

             switch ($elemento) {
                    case 1:
                        # code...
                        $tabla='problemas';
                        break;

                    case 2:
                        $tabla='cursos';
                        break;

                    case 3:
                        $tabla='objetos';
                        break;
                    
                    default:
                        # code...
                        $error = (object) [
                        'result' => 'error',
                        'data' => $data
                        ];

                        return $error;
                        $this->dbc = null;
                        break;
             };

            $stmt = $dbc->prepare(
                "UPDATE $tabla SET estaActivo='$obj->activo'
                WHERE ID='$obj->id'"
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
                        'data' => 'activo'
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