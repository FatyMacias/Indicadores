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



function obtenerCursos($obj)
{
    try {
        $dbc = Database::Conectar();

        /*$sql = "SELECT problemas.*
            FROM problemas_cursos cursosP
            INNER JOIN cursos ON cursosP.id_curso=cursos.ID
            INNER JOIN problemas ON cursosP.id_problema=problemas.ID AND problemas.status=1 AND problemas.estaActivo=1
            WHERE cursos.status=1 AND cursos.idDocente='$obj->idDocente' 
            ORDER BY cursosP.ID desc";*/

        $sql = "SELECT cursosP.*
            FROM problemas_cursos cursosP
            WHERE cursosP.id_curso='$obj->idCurso'
            ORDER BY cursosP.ID desc";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $problemas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $count=count($problemas);
        $data=[];
        for ($i=0; $i <$count ; $i++) { 
            # code...
            $idProblema=$problemas[$i]->id_problema;
            $sqlP = "SELECT problemas.*
            FROM problemas
            WHERE problemas.ID='$idProblema'
            ORDER BY problemas.ID desc";

            $stmtP = $dbc->prepare($sqlP);
            $stmtP->execute();
            $dataP = $stmtP->fetchAll(PDO::FETCH_OBJ);

            $data[$i]=$dataP[0];

        };

        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}