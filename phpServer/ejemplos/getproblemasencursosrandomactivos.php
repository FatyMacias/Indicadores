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
        //$sql="SELECT * FROM usuarios";
        // INNER JOIN problemas_cursos cursosP ON cursosP.id_curso=cursosPA.id_curso
        $sql="SELECT GROUP_CONCAT(childObj.url_img) as url,GROUP_CONCAT(childObj.etiqueta) AS etiquetas,GROUP_CONCAT(childObj.ID) AS idChild,GROUP_CONCAT(childObj.cantidad) AS cantidadObjLista,GROUP_CONCAT(childObj.id_relacion) AS id_relacion,
        GROUP_CONCAT(objP.cantidad) AS cantidadObj,
        problemas.*,problemas.ID AS idProblema,
        cursosPA.*,cursosPA.ID AS idRelacion
            FROM curso_problema_alumno cursosPA

            INNER JOIN cursos ON cursosPA.id_curso=cursos.ID

            INNER JOIN problemas ON cursosPA.id_problema=problemas.ID
            AND problemas.status=1 AND problemas.estaActivo=1
            INNER JOIN objetosproblemas objP ON problemas.ID=objP.id_problemas
            INNER JOIN objetos_lista childObj ON childObj.ID = objP.id_objetos_lista
            WHERE cursos.status=1 AND cursosPA.id_alumno='$obj->idAlumno' AND problemas.mundo ='$obj->idMundo' AND problemas.estaActivo = 1 AND cursosPA.activo=0 AND cursosPA.id_curso='$obj->idCurso'
            GROUP BY problemas.ID 
            ORDER BY RAND() LIMIT 1";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $count=count($data);

        if ($count>0) {
            # code...

            $idProblemaR=$data[0]->idProblema;
            $sqlMuestra="SELECT * FROM muestra WHERE id_problema='$idProblemaR'";

            $stmtM = $dbc->prepare($sqlMuestra);
            $stmtM->execute();
            $dataM = $stmtM->fetchAll(PDO::FETCH_OBJ);

            $idR=explode(",",$data[0]->idChild);
            $etiqueta=explode(",",$data[0]->etiquetas);
            $cantidad=explode(",", $data[0]->cantidadObj);
            $url=explode(",", $data[0]->url);
            $numO=$data[0]->numeroObjetos;
            $idRelacion=explode(",",$data[0]->id_relacion);
            $count=count($dataM);
            $idPadress=[];
            for ($j=0; $j < $count; $j++) {

                $idPadre=$dataM[$j]->id_objeto_padre;
                $repeticion=$dataM[$j]->repeticion;
                $muestra=$dataM[$j]->muestra;

                $data[0]->objetosPadreHijos[$j]=array(
                    'repeticion' =>$repeticion,
                    'muestra'=>$muestra,
                    'idPadre'=>$idPadre
                );
                $contI=0;
                for ($k=0; $k <$numO ; $k++) {

                    if ($idPadre==$idRelacion[$k]) {

                        $data[0]->objetosPadreHijos[$j]['objetosHijo'][$contI]=array(
                                'idChild' => $idR[$k],
                                'etiqueta'=>$etiqueta[$k],
                                'cantidad'=>$cantidad[$k],
                                'url'=>$url[$k],
                                'idRelacion'=>$idRelacion[$k]
                            );
                        $contI++;
                    }else{};
                };
            };

            for ($i=0; $i <$numO ; $i++) {

                $data[0]->objetosLista[$i]=array(
                    'idChild' => $idR[$i],
                    'etiqueta'=>$etiqueta[$i],
                    'cantidad'=>$cantidad[$i],
                    'url'=>$url[$i],
                    'idRelacion'=>$idRelacion[$i]
                );
            };
            $resp=array('msg' =>'ok','data'=>$data );
        }else{
            $resp= array('msg' =>'error','data'=>'Ya has contestado todos los problemas de este mundo!' );
        }

        return $resp;
        $this->dbc = null;

         // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}