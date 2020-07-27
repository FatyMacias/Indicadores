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

function obtenerCursos($obj){

    try{

        $dbc = Database::Conectar();

        $sql="SELECT * FROM cursos_alumnos WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'";

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
                $response = array(
                    'msg' =>'error',
                    'data'=>'Pide verificar la configuración del curso'
                );

                return  $response;
                $this->dbc = null;
                break;
        };


        $sqlPC="SELECT * FROM cursos WHERE ID='$obj->idCurso'";

        $stmt1 = $dbc->prepare($sqlPC);
        $stmt1->execute();

        $dataPC = $stmt1->fetchAll(PDO::FETCH_OBJ);
       /* $response = array('data' =>$dataPC,'dataAc'=>$data);
        return  $response;
            $this->dbc = null;*/

        $cantidadProblemas=$dataPC[0]->$condicion;

        if ($cantidadProblemas==0) {
            # code...
            $response = array(
                    'msg' =>'error',
                    'data'=>'Pide verificar la configuración del curso'
                );

                return  $response;
                $this->dbc = null;
        }else{

            //Si la cantidad de problemas resueltos por el alumno es mayor o igual que la cantidad de problemas en ese mundo se actualiza el nivel del alumno! 

            if ($cantidad>=$cantidadProblemas) {
                # code...
                if ($nivelActual==4) {
                    # code...
                    $nivelActual=4;
                }else{
                    $nivelActual++;
                };


                $update = $dbc->prepare(
                    "UPDATE `cursos_alumnos` SET nivel_alumno_curso='$nivelActual'
                    WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'"
                );

                if (!$update->execute()) {
                    $error = (object) [
                        'result' => 'error',
                        'data' => $data
                    ];

                    return $error;
                    $this->dbc = null;
                }else{
                    $cantidad=0;
                    $response = (object) [
                            'result' => 'ok',
                            'data' => $nivelActual,
                            'cantidad'=>$cantidad,
                            'curso'=>$data
                        ];

                    return  $response;
                    $this->dbc = null;
                };
            }else{
                $nivelActual=$nivelActual;
                $response = (object) [
                            'result' => 'ok',
                            'data' => $nivelActual,
                            'cantidad'=>$cantidad,
                            'curso'=>$data
                        ];

                return  $response;
                $this->dbc = null;
            };
        };
        
        // PDO error handling
    }catch(PDOException $errMsg ) {

        return $errMsg;
    };   
};