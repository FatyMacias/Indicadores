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
        /*$sql ="SELECT `objs.ID,objs.nombre` as `objetoP`,`objList.`* FROM `objetos` `objs` 
                        inner join `objetos_lista` `objList` on `objs.ID=objList.id_relacion`
                        order by `objs.ID`";*/

        $sql = "SELECT GROUP_CONCAT(objetos_lista.url_img) AS url_img,GROUP_CONCAT(objetos_lista.ID) AS idChild,GROUP_CONCAT(objetos_lista.esta_activo) AS activo,GROUP_CONCAT(objetos_lista.etiqueta) AS etiqueta,GROUP_CONCAT(objetos_lista.cantidad) AS cantidad,GROUP_CONCAT(objetos_lista.id_relacion) AS id_relacion, objetos.*
                FROM objetos 
            INNER JOIN objetos_lista ON objetos_lista.id_relacion = objetos.ID
            WHERE objetos_lista.esta_activo=1 AND objetos.esta_activo=1
            GROUP BY objetos.ID
            ORDER BY objetos_lista.id_relacion desc";
            $stmt = $dbc->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $count=count($data);
            for ($i=0; $i <$count ; $i++) { 
                # code...
                $idC=explode(",",$data[$i]->idChild);
                $etiqueta=explode(",",$data[$i]->etiqueta);
                $cantidad=explode(",", $data[$i]->cantidad);
                $url=explode(",", $data[$i]->url_img);
                $idP=explode(",", $data[$i]->id_relacion);
                $nombre=$data[$i]->nombre;
                $urlP=$data[$i]->url_imagen_padre;
                $tipo=$data[$i]->tipo_objeto;
                $countOH=count($idC);
                for ($j=0; $j <$countOH ; $j++) { 
                    # code...
                    $data[$i]->objetosHijos[$j]= array('ID'=>$idP[$j],'idChild' =>$idC[$j] , 'etiqueta'=>$etiqueta[$j],'cantidad'=>$cantidad[$j],'urlChild'=>$url[$j],'urlPadre'=>$urlP,'nombre'=>$nombre,'tipo'=>$tipo);
                };
            };
            return $data;
            $this->dbc = null;

            // PDO error handling
        
    } catch ( PDOException $errMsg ) {

        return $errMsg;
    }   
}