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

        //Recuperamos problemas
        $sqlP = "SELECT * FROM problemas WHERE status!=1 ORDER BY ID desc";

        $stmtP = $dbc->prepare($sqlP);
        $stmtP->execute();
        $dataP = $stmtP->fetchAll(PDO::FETCH_OBJ);

        //Recuperamos cursos
        $sqlC = "SELECT * FROM cursos WHERE status!=1 ORDER BY ID desc";

        $stmtC = $dbc->prepare($sqlC);
        $stmtC->execute();
        $dataC = $stmtC->fetchAll(PDO::FETCH_OBJ);

        //Recuperamos objetos
        $sqlO = "SELECT * FROM objetos WHERE esta_activo=0 ORDER BY ID desc";

        $stmtO = $dbc->prepare($sqlO);
        $stmtO->execute();
        $dataO = $stmtO->fetchAll(PDO::FETCH_OBJ);

        $data= array('problemas' =>$dataP,'cursos'=>$dataC,'objetos'=>$dataO);

        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}