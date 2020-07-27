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

        $sql ="SELECT GROUP_CONCAT(childObj.url_img) as url,GROUP_CONCAT(childObj.etiqueta) AS etiquetas,GROUP_CONCAT(childObj.ID) AS idChild,GROUP_CONCAT(childObj.cantidad) AS cantidadObjLista, GROUP_CONCAT(objP.cantidad) AS cantidadObj, problemas.* FROM objetosproblemas  objP INNER JOIN objetos_lista childObj ON childObj.ID = objP.id_objetos_lista INNER JOIN problemas ON problemas.ID=objP.id_problemas
            WHERE problemas.mundo ='$obj->idMundo' AND problemas.estaActivo = 1
            GROUP BY problemas.ID 
            ORDER BY RAND() LIMIT 1";

        $stmt = $dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$count=count($data);
        //for ($i=0; $i <$count+1; $i++) { 
            # code...
           /* $idChilds=$data[0]->idChild;
            $etiqueta=$data[0]->etiquetas;
            $cantidad=$data[0]->cantidadObj;
            $url=$data[0]->url;*/
        //Exlplode array
            $idR=explode(",",$data[0]->idChild);
            $etiqueta=explode(",",$data[0]->etiquetas);
            $cantidad=explode(",", $data[0]->cantidadObj);
            $url=explode(",", $data[0]->url);
            $numO=$data[0]->numeroObjetos;
            for ($i=0; $i <$numO ; $i++) { 
                # code...
                $data[0]->objetosLista[$i]= array('idChild' => $idR[$i],'etiqueta'=>$etiqueta[$i],'cantidad'=>$cantidad[$i],'url'=>$url[$i] );;
            };
            return $data;
            /*$array2[0]=array('id_relacion' =>explode(",", $idChilds),'etiqueta'=>explode(",", $etiqueta),'cantidad' =>explode(",", $cantidad),'urls' =>explode(",", $url));
           $data[0]->idObjeto=$array2[0];  */  
        //};
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }   
}