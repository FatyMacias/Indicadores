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
        if (empty($obj->nombre) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{
            $stmt = $dbc->prepare("INSERT INTO `cursos` (nombre, status,token,idDocente,fecha,problemsM1,problemsM2,problemsM3,problemsM4,estaActivo)
                VALUES ('$obj->nombre', '$obj->status','$obj->token', '$obj->idDocente','$obj->fecha','1','1','1','1','$obj->estaActivo')");
            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
            return $error;
            $this->dbc = null;
        }else{
            //objetoHijo($id,$obj);
           $cursoGuardado = (object) [
                    'result' => 'ok',
                    'data' => 'Guardado'
                ];
            return  $cursoGuardado;
            $this->dbc = null;
        };
    }
} catch ( PDOException $errMsg ) {
        $error = (object) [
                    'result' => 'error',
                    'data' => $errMsg
                ];
        return $error;
    }   
};