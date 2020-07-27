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


        if ( empty($obj->tipoAuth ) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{


            if( $obj->tipoAuth == "1" ){//Matricula
                $userMatricula = $dbc->prepare("
                SELECT * FROM `usuarios` WHERE matricula = '$obj->matricula' AND contraseña = '$obj->password'");

                $userMatricula->execute();

                $matriculaDatas = $userMatricula->fetchAll(PDO::FETCH_OBJ);

                return $matriculaDatas;

            }else{//Correo
                $userCorreo = $dbc->prepare("
                SELECT * FROM `usuarios` WHERE correo = '$obj->correo' AND contraseña = '$obj->password'");

                $userCorreo->execute();

                $correoDatas = $userCorreo->fetchAll(PDO::FETCH_OBJ);

                return $correoDatas;
            }
   
            
            // PDO error handling
        }
    } catch ( PDOException $errMsg ) {
        $data = (object) [
                'result' => 'Error',
                'error'=>$errMsg
            ];
        return $data;
    }   
}