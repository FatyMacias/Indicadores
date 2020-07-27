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

       /* $sql = "SELECT cursos.nombre as nombreCurso,cursos.idDocente as idDocente, cursosA.idAlumno as idAlumno,cursosA.idCurso as idCurso,cursosA.aceptado as aceptado, usuarios.nombre as nombreAlumno,usuarios.apellido_paterno as apellidoP, usuarios.apellido_materno as apellidoM
            FROM cursos_alumnos cursosA
            INNER JOIN cursos ON cursosA.idCurso=cursos.ID
            INNER JOIN usuarios ON cursosA.idAlumno=usuarios.ID
            WHERE cursosA.status=1 AND cursosA.idCurso ='$obj->idCurso' AND cursos.idDocente='$obj->idDocente'  AND cursos.status=1 
            ORDER BY cursosA.ID desc";*/
        $sql="SELECT cursosA.* FROM cursos_alumnos cursosA WHERE cursosA.idCurso='$obj->idCurso' AND cursosA.status='1' AND cursosA.aceptado='1'";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $alumnos= $stmt->fetchAll(PDO::FETCH_OBJ);
        $count=count($alumnos);
        $data=[];
        for ($i=0; $i <$count ; $i++) { 
            # code...
            $idAlumno=$alumnos[$i]->idAlumno;
            $sqlA = "SELECT usuarios.nombre as nombreAlumno,usuarios.apellido_paterno as apellidoP, usuarios.apellido_materno as apellidoM,usuarios.ID as idAlumno
                FROM usuarios
            WHERE usuarios.ID='$idAlumno'
            ORDER BY usuarios.ID desc";

            $stmtA = $dbc->prepare($sqlA);
            $stmtA->execute();
            $dataA= $stmtA->fetchAll(PDO::FETCH_OBJ);



            $sqlPCA = "SELECT cpa.*, problemas.nombre,problemas.mundo
                FROM curso_problema_alumno cpa
                INNER JOIN problemas ON cpa.id_problema=problemas.ID WHERE id_alumno='$idAlumno' AND id_curso='$obj->idCurso'";
            $stmtPCA = $dbc->prepare($sqlPCA);
            $stmtPCA->execute();
            $dataPCA= $stmtPCA->fetchAll(PDO::FETCH_OBJ);

            $data[$i] = array('alumno' => $dataA[0],'problemas_alumno'=> $dataPCA);


        };
        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}