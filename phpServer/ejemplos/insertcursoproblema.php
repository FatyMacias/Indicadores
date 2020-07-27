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
        if (empty($obj->idCurso) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{
            
            $idCurso=$obj->idCurso;

            $stmtD = $dbc->prepare(
                    "DELETE FROM `problemas_cursos` WHERE id_curso='$idCurso'");
            
            if (!$stmtD->execute()){
                $error = (object) [
                            'result' => 'error',
                            'data' => $data
                            ];
                
                return $error;
                $this->dbc = null;
            }else{

                $problemas=$obj->problemas;
                $count=count($problemas);

                for ($i=0; $i <$count ; $i++) { 
                    # code...
                    $idProblema=$problemas[$i];
                    //$this->dbc = null;
                    $stmt = $dbc->prepare("INSERT INTO `problemas_cursos` (id_curso,id_problema)
                            VALUES ('$idCurso', '$idProblema')");

                    if (!$stmt->execute()) {
                        $error = (object) [
                                'result' => 'error',
                                'data' => $data
                                ];
                        
                        return $error;
                        $this->dbc = null;
                    }else{

                       
                        $sentAlumnos=$dbc->prepare("SELECT idAlumno FROM cursos_alumnos WHERE aceptado=1 AND idCurso='$idCurso'");

                        $sentAlumnos->execute();

                        $alumnos=$sentAlumnos->fetchAll(PDO::FETCH_OBJ);

                        $countA=count($alumnos);

                        for ($a=0; $a <$countA ; $a++) {
                            # code...
                            $idA=$alumnos[$a]->idAlumno;


                            $sqlExist = $dbc->prepare("SELECT * FROM curso_problema_alumno WHERE id_curso= '$idCurso' AND id_alumno='$idA' AND id_problema=$idProblema");

                            $sqlExist->execute();

                            $existe=$sqlExist->fetchAll(PDO::FETCH_OBJ);
                            $countReg=count($existe);

                            if ($countReg>0) {
                                # code...

                            }else{

                                $insertReg = $dbc->prepare("INSERT INTO `curso_problema_alumno` (id_curso,id_alumno,id_problema,respuesta,activo) VALUES ('$idCurso','$idA','$idProblema',0,0)");

                                $insertReg->execute();

                            }
                        };

                    };
                };
            };

           $guardado = (object) [
                        'result' => 'ok',
                        'data' => 'Guardado',
                        $countReg,
                        $alumnos
                        ];
            
            return  $guardado;
            $this->dbc = null;
        };
} catch ( PDOException $errMsg ) {
        $error = (object) [
                    'result' => 'error',
                    'data' => $errMsg
                ];
        return $error;
    }   
};