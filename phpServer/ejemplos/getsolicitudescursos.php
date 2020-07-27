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

        $sql = "SELECT cursos.nombre as nombre,cursos.idDocente as idDocente, cursosA.idAlumno as idAlumno,cursosA.idCurso as idCurso,cursosA.aceptado AS aceptado, usuarios.nombre as nombreA,usuarios.apellido_paterno as apellidoP, usuarios.apellido_materno as apellidoM
            FROM cursos_alumnos cursosA
            INNER JOIN cursos ON cursosA.idCurso=cursos.ID
            INNER JOIN usuarios ON cursosA.idAlumno=usuarios.ID
            WHERE cursosA.status=1 AND cursos.idDocente='$obj->idDocente' AND cursosA.aceptado=0 AND cursos.status=1 
            ORDER BY cursosA.ID desc";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ); 
        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}