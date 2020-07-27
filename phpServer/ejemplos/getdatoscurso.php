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

$action = call_user_func( "obtenerCursos", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);

function obtenerCursos($obj){

    try{

        $dbc = Database::Conectar();

        $sql="SELECT * FROM cursos_alumnos WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        $response = array(
            'msg' =>'ok',
            'data'=>$data[0]
        );
        
        return  $response;
        $this->dbc = null;
        
        // PDO error handling
    }catch(PDOException $errMsg ) {

        return $errMsg;
    };   
};