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



function obtenerMenu($obj){

    try {
        $dbc = Database::Conectar();

        if ( empty($obj->idRelacion) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

            //Actualizamos la los problemas por alumno en curso
            $stmt = $dbc->prepare("UPDATE `curso_problema_alumno` SET respuesta='$obj->respuesta',activo='1' WHERE ID='$obj->idRelacion'");

            $stmt->execute();


            $mundo=$obj->mundo;
            $cantidad=($obj->cantidad)+1;

            switch ($mundo) {
                case '1':
                    # code...
                        $mundoP='cantidad_nivel1';
                    break;

                case '2':
                        # code...
                    $mundoP='cantidad_nivel2';
                    break;

                case '3':
                    # code...
                    $mundoP='cantidad_nivel3';
                    break;

                case '4':
                    # code...
                    $mundoP='cantidad_nivel4';
                    break;
                            
                default:
                    # code...
                    break;
            };

            //Actualizamos los registros del alumno en el curso
            $updateCursosNivel = $dbc->prepare("UPDATE `cursos_alumnos` SET $mundoP='$cantidad' WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'");

            $updateCursosNivel->execute();

            //Validamos el mundo
            //Obtener datos del alumno en el curso
            $sql="SELECT * FROM cursos_alumnos WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'";

            $datosCurso= $dbc->prepare($sql);
            $datosCurso->execute();
            $data = $datosCurso->fetchAll(PDO::FETCH_OBJ);

            //Obtenemos el nivel actual y la cantidad de problemas resueltos
            $nivelActual=$data[0]->nivel_alumno_curso;


            switch ($nivelActual) {
                case '1':
                    # code...
                    $condicion='problemsM1';
                    $cantidadActual=$data[0]->cantidad_nivel1;
                    break;

                case '2':
                    # code...
                    $condicion='problemsM2';
                    $cantidadActual=$data[0]->cantidad_nivel2;
                    break;

                case '3':
                    # code...
                    $condicion='problemsM3';
                    $cantidadActual=$data[0]->cantidad_nivel3;
                    break;

                case '4':
                    # code...
                    $condicion='problemsM4';
                    $cantidadActual=$data[0]->cantidad_nivel4;
                    break;
                            
                default:
                    # code...
                    break;
            };

            //Obtener datos del curso
            $sqlPC="SELECT * FROM cursos WHERE ID='$obj->idCurso'";
            $stmt1 = $dbc->prepare($sqlPC);
            $stmt1->execute();
            $dataPC = $stmt1->fetchAll(PDO::FETCH_OBJ);
            $problemXcurso=$dataPC[0]->$condicion;


            //Actualizamos el mundo si la cantidadActual es mayor
            if ($cantidadActual>=$problemXcurso) {
                if ($nivelActual==4) {
                    $nivelActual=4;
                }else{
                    $nivelActual=$nivelActual+1;
                };

                $updateNivel = $dbc->prepare("UPDATE `cursos_alumnos` SET nivel_alumno_curso='$nivelActual' WHERE idCurso='$obj->idCurso' AND idAlumno='$obj->idAlumno'");

                $updateNivel->execute();
            }else{}

            $response = array(
                'msg' =>'ok' ,
                'data'=>'Actualizado',
                $cantidadActual,
                $nivelActual
            );

            return $response;
            $this->dbc = null;
        };
    } catch ( PDOException $errMsg ) {
        return $errMsg;
    }   
}