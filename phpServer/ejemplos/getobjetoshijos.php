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

        $sql = "SELECT objetos_lista.url_img,objetos_lista.ID AS idChild,objetos_lista.esta_activo,objetos_lista.etiqueta,objetos_lista.cantidad,objetos_lista.id_relacion, objetos.ID, objetos.nombre,objetos.descripcion,objetos.esta_activo,objetos.url_imagen_padre,objetos.tipo_objeto 
                FROM objetos 
                    INNER JOIN objetos_lista ON 
                    objetos_lista.id_relacion = objetos.ID
                        ORDER BY objetos.ID
                
                ";


                $stmt = $dbc->prepare($sql);
                $stmt->execute();

    
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    
                return $data;

                $this->dbc = null;

            // PDO error handling
        
    } catch ( PDOException $errMsg ) {

        return $errMsg;
    }   
}