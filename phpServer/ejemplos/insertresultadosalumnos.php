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
    try{

        $dbc = Database::Conectar();

        if (empty($obj->idCurso) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{


        /*$sql="SELECT * FROM cursos_alumnos WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        //Nivel actual del alumno(mundo)
        $nivelActual=$data[0]->nivel_alumno_curso;
        $cantidad=0;

        switch ($nivelActual) {
            case '1':
                # code...
                $cantidad=$data[0]->cantidad_nivel1;
                $condicion='problemsM1';
                break;

            case '2':
                # code...
                $cantidad=$data[0]->cantidad_nivel2;
                $condicion='problemsM2';
                break;

            case '3':
                # code...
                $cantidad=$data[0]->cantidad_nivel3;
                $condicion='problemsM3';
                break;

            case '4':
                # code...
                $cantidad=$data[0]->cantidad_nivel4;
                $condicion='problemsM4';
                break;
            
            default:
                # code...
                $cantidad=0;
                //$condicion='problemsM1';
                break;
        };*/
            
            $idCurso=$obj->idCurso;
            $mundo->$obj->mundo;
            $idAlumno=$obj->idAlumno;
            $newIntentos1=$obj->intentos1;
            $newIntentos2=$obj->intentos2;
            $newIntentos3=$obj->intentos3;
            $newIntentos4=$obj->intentos4;

            $stmt = $dbc->prepare("UPDATE `cursos_alumnos` SET cantidad_nivel1='$newIntentos1',cantidad_nivel2='$newIntentos2',cantidad_nivel3='$newIntentos3',cantidad_nivel4='$newIntentos4'
                WHERE idAlumno='$idAlumno' AND idCurso='$idCurso'" );
            
            if (!$stmtR->execute()){
                $error = (object) [
                            'result' => 'error',
                            'data' => $data
                            ];
                
                return $error;
                $this->dbc = null;
            }else{

            $guardado = (object) [
                        'result' => 'ok',
                        'data' => 'Guardado'
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