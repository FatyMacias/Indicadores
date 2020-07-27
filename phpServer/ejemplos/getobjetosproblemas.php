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



function obtenerMenu($obj)
{
    try {
        $dbc = Database::Conectar();
        /*
        $sql = "SELECT 
        GROUP_CONCAT(childObj.url_img) as url, GROUP_CONCAT(childObj.etiqueta) AS etiquetas,GROUP_CONCAT(childObj.ID) AS idChild,GROUP_CONCAT(childObj.cantidad) AS cantidadObjLista,
        GROUP_CONCAT(objPadre.ID) AS idPadre,GROUP_CONCAT(objPadre.nombre) AS nombrePadre,GROUP_CONCAT(objPadre.url_imagen_padre) AS urlPadre,
         GROUP_CONCAT(objP.cantidad) AS cantidadObj,GROUP_CONCAT(objP.etiqueta_problema) AS tags,
         problemas.nombre AS nombreP, problemas.ID AS idProblem,problemas.muestra,problemas.repeticion,problemas.descripcion,problemas.simulaciones,problemas.muestra,problemas.porcentaje,problemas.respuestaT,problemas.mundo,problemas.nivelDelProblema,problemas.numeroObjetos,
         GROUP_CONCAT(muestra.muestra) As muestraObjeto,
         GROUP_CONCAT(muestra.id_objeto_padre) As idMuestraObjeto,
         GROUP_CONCAT(muestra.repeticion) As repeticionObjeto

         FROM objetosproblemas  objP 
          INNER JOIN objetos_lista childObj ON childObj.ID = objP.id_objetos_lista 
          INNER JOIN problemas ON problemas.ID=objP.id_problemas
          INNER JOIN objetos objPadre ON childObj.id_relacion = objPadre.ID
          RIGHT JOIN muestra on problemas.ID=muestra.id_problema
            GROUP BY problemas.ID
            ORDER BY problemas.ID desc";*/


        $sql = "SELECT 
          GROUP_CONCAT(objProblem.id_objetos_lista) AS idChild,
          GROUP_CONCAT(objProblem.cantidad) AS cantidadObj,
          GROUP_CONCAT(objProblem.etiqueta_problema) AS tags,

          GROUP_CONCAT(objChild.id_relacion) AS idRelacion,
          GROUP_CONCAT(objChild.url_img) AS urlChild,
          GROUP_CONCAT(objChild.etiqueta) AS etiquetas, 

          GROUP_CONCAT(objPadre.ID) AS idPadre,
          GROUP_CONCAT(objPadre.nombre) AS nombrePadre,
          GROUP_CONCAT(objPadre.url_imagen_padre) AS urlPadre,
          GROUP_CONCAT(objPadre.tipo_objeto) AS tipoO,

          problemas.nombre AS nombreP,
          problemas.ID AS idProblem,
          problemas.descripcion,
          problemas.simulaciones,
          problemas.porcentaje,
          problemas.respuestaT,
          problemas.mundo,
          problemas.nivelDelProblema,
          problemas.numeroObjetos,
          problemas.estaActivo
          FROM problemas
          INNER JOIN objetosproblemas objProblem ON objProblem.id_problemas=problemas.ID
          INNER JOIN objetos_lista objChild ON objProblem.id_objetos_lista=objChild.ID
          INNER JOIN objetos objPadre ON objChild.id_relacion=objPadre.ID
          WHERE problemas.status=1
          GROUP BY problemas.ID
          ORDER BY problemas.ID desc";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $count=count($data);
        for ($i=0; $i <$count; $i++) { 
            # code...
            $idR=explode(",",$data[$i]->idChild);
            $etiqueta=explode(",",$data[$i]->etiquetas);
            $cantidad=explode(",", $data[$i]->cantidadObj);
            $url=explode(",", $data[$i]->urlChild);
            $tag=explode(",", $data[$i]->tags);

            $idRP=explode(",",$data[$i]->idPadre);
            $nombreP=explode(",",$data[$i]->nombrePadre);
            $urlP=explode(",", $data[$i]->urlPadre);
            $tipo=explode(",", $data[$i]->tipoO);

            $idProblema=$data[$i]->idProblem;

            $numO=count($idR);

            for ($j=0; $j <$numO ; $j++) { 
                # code...
                $data[$i]->idObjeto[$j]= array('idChild' => $idR[$j],'etiqueta'=>$etiqueta[$j],'cantidad'=>$cantidad[$j],'urlChild'=>$url[$j],'ID'=>$idRP[$j],'urlPadre'=>$urlP[$j],'nombre'=>$nombreP[$j],'tagExtra'=>$tag[$j],'tipo'=>$tipo[$j]);
            };

            $sqlM="SELECT muestra.*,objetos.nombre
             FROM muestra 
             INNER JOIN objetos ON muestra.id_objeto_padre=objetos.ID
             WHERE muestra.id_problema='$idProblema'
                ORDER BY muestra.id_problema desc";

            $stmtMuestra = $dbc->prepare($sqlM);
            $stmtMuestra->execute();
            $dataMuestra = $stmtMuestra->fetchAll(PDO::FETCH_OBJ);

            $countMuestra=count($dataMuestra);

            for ($k=0; $k <$countMuestra; $k++) { 
                # code...
                $nombreOP=$dataMuestra[$k]->nombre;
                $idOP=$dataMuestra[$k]->id_objeto_padre;
                $muestra=$dataMuestra[$k]->muestra;
                $repeticion=$dataMuestra[$k]->repeticion;
                $problemaM=$dataMuestra[$k]->id_problema;

                $data[$i]->objetosPadre[$k]= array('idPadre' => $idOP,'muestra'=>$muestra,'repeticion'=>$repeticion,'nombre'=>$nombreOP);
            };
            $activo=$data[$i]->estaActivo;
            if ($activo=='1') {
              # code...
              $data[$i]->estaActivo=true;
            }else{
              $data[$i]->estaActivo=false;
            }
        };

        return $data;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}