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
            //$stmt->execute();
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

            $sqlEx="SELECT COUNT(*) AS countR FROM cursos_alumnos WHERE idCurso= '$ID' AND idAlumno='$obj->idAlumno' AND status=1";

            $stmtEx = $dbc->prepare($sqlEx);
            $stmtEx->execute();
            $dataEx= $stmtEx->fetchAll(PDO::FETCH_OBJ);
            $count=$dataEx[0]->countR;

            if ($count>0) {
                $data = (object) [
                    'result' => 'curso existente',
                    'data' => $count
                ];
                return $data;
                $this->dbc = null;
            }else{


                $sqlEx="SELECT COUNT(*) AS countR FROM cursos_alumnos WHERE idCurso= '$ID' AND idAlumno='$obj->idAlumno'";

                $stmtEx = $dbc->prepare($sqlEx);
                $stmtEx->execute();
                $dataEx= $stmtEx->fetchAll(PDO::FETCH_OBJ);
                $count=$dataEx[0]->countR;
                if ($count>0) {

                     $stmt1 = $dbc->prepare(
                        "UPDATE `cursos_alumnos` SET aceptado='0',status='1',fecha_solicitud='$obj->fecha'
                        WHERE idCurso='$ID' AND idAlumno='$obj->idAlumno'");
                }else{

                    $stmt1 = $dbc->prepare("INSERT INTO `cursos_alumnos` (idCurso,idAlumno,status,aceptado,fecha_solicitud,nivel_alumno_curso,cantidad_nivel1,cantidad_nivel2,cantidad_nivel3,cantidad_nivel4)
                        VALUES ('$ID', '$obj->idAlumno','1', '0','$obj->fecha','1','0','0','0','0')");
                }
                

                if (!$stmt1->execute()) {
                    $error = (object) [
                        'result' => 'error',
                        'data' => null
                    ];
                    return $error;
                    $this->dbc = null;
                }else{

                    $cursoGuardado = (object) [
                        'result' => 'ok',
                        'data' => 'Guardado',
                        'count'=>$dataEx
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