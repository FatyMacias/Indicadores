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

$action = call_user_func( "aceptar", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function aceptar($obj){

    try{

        $dbc = Database::Conectar();
        
        if (empty($obj->idCurso) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

            $stmt = $dbc->prepare(
                "UPDATE `cursos_alumnos` SET aceptado='$obj->aceptado',nivel_alumno_curso='1',cantidad_nivel1='0',cantidad_nivel2='0',cantidad_nivel3='0',cantidad_nivel4='0'
                WHERE idCurso='$obj->idCurso'"
            );

            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
            
                return $error;
                $this->dbc = null;
            }else{

                $ID=$obj->idCurso;

                $sqlProblems = "SELECT GROUP_CONCAT(id_problema) AS problemas FROM problemas_cursos WHERE id_curso='$ID'";

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

                        $idProblema=$problemas[$i];

                        $sqlExist = $dbc->prepare("SELECT * FROM curso_problema_alumno WHERE id_curso= '$ID' AND id_alumno='$obj->idAlumno' AND id_problema='$idProblema'");

                            $sqlExist->execute();

                            $existe=$sqlExist->fetchAll(PDO::FETCH_OBJ);
                            $countReg=count($existe);

                            if ($countReg>0) {
                                # code...

                            }else{

                                $saveAlumnosCursosProblemas =$dbc->prepare("INSERT INTO `curso_problema_alumno` (id_curso,id_alumno,id_problema,respuesta,activo) VALUES ('$ID','$obj->idAlumno', '$idProblema','0', '0')");

                                if (!$saveAlumnosCursosProblemas->execute()) {

                                    $error = (object) [
                                        'result' => 'error',
                                        'data' => $data
                                    ];

                                    return $error;
                                    $this->dbc = null;
                                
                                }else{};
                            }
                    };

                    $response = (object) [
                        'result' => 'ok',
                        'data' => 'Guardado'
                    ];
                    return  $response;
                    $this->dbc = null;
                };
            };
        };
    }catch ( PDOException $errMsg ) {

        $error = (object) [
            'result' => 'error',
            'data' => $errMsg
        ];

        return $error;
    };
}