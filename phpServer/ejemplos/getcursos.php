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

        $sql = "SELECT * FROM cursos WHERE status=1 AND idDocente='$obj->idDocente' ORDER BY ID desc";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $count=count($data);
        for ($i=0; $i <$count ; $i++) { 
            # code...

            $activo=$data[$i]->estaActivo;
            if ($activo=='1') {

                $data[$i]->estaActivo=true;
            }else{
                $data[$i]->estaActivo=false;
            }
        };
        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}