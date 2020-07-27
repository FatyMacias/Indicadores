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
        if (empty($obj->token) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

            $sql = "SELECT ID FROM cursos WHERE status=1 AND token='$obj->token'";

            $stmt = $dbc->prepare($sql);

            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
                return $error;
                $this->dbc = null;
            }else{
                $idCurso = $stmt->fetchAll(PDO::FETCH_OBJ);
                $ID=$idCurso[0]->ID;

                $stmt1 = $dbc->prepare("INSERT INTO `cursos_alumnos` (idCurso,idAlumno,status,aceptado)
                VALUES ('$ID', '$obj->idAlumno','1', '0')");
                if (!$stmt1->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
                return $error;
                $this->dbc = null;
                }else{
                    $sqlProblems = "SELECT GROUP_CONCAT(id_problema) AS problemas 
                        FROM problemas_cursos WHERE id_curso='$ID'";

                    $stmProblemas=$dbc->prepare($sqlProblems);
                    
                    if (!$stmProblemas->execute()) {
                        $error = (object) [
                            'result' => 'error',
                            'data' => $data
                        ];
                        return $error;
                        $this->dbc = null;
                    }else{
                        $dataP = $stmProblemas->fetchAll(PDO::FETCH_OBJ);
                        $problemas=explode(",",$dataP[0]->problemas);

                        $count=count($problemas);
                        for ($i=0; $i < $count ; $i++) { 
                            # code...
                            $idProblema=$problemas[$i];
                            $saveAlumnosCursosProblemas = 
                            $dbc->prepare(
                                "INSERT INTO `curso_problema_alumno` (id_curso,id_alumno,id_problema,respuesta,activo)
                                VALUES ('$ID','$obj->idAlumno', '$idProblema','0', '0')");
                            if (!$saveAlumnosCursosProblemas->execute()) {
                                $error = (object) [
                                    'result' => 'error',
                                    'data' => $data
                                ];

                                return $error;
                                $this->dbc = null;
                            }else{};
                        };

                         $cursoGuardado = (object) [
                                'result' => 'ok',
                                'data' => 'Guardado'
                            ];

                        return  $cursoGuardado;
                        $this->dbc = null;
                    };
                };
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