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

                $sql = "SELECT childObj.* ,childObj.ID AS idChild,objP.cantidad AS cantidadObj, problemas.* FROM objetosproblemas  objP INNER JOIN objetos_lista childObj ON 
                    childObj.ID = objP.id_objetos_lista
                    INNER JOIN problemas ON problemas.ID=objP.id_problemas
                        WHERE problemas.mundo = 1 AND problemas.estaActivo = 1 ORDER BY RAND() LIMIT 1";

                        /*WHERE problemas.mundo = 1 AND problemas.estaActivo = 1 ORDER BY RAND() LIMIT 1 */

                 $stmt = $dbc->prepare($sql);

                /*$stmt = $dbc->prepare("
                SELECT * FROM `objetosproblemas`");*/

                $stmt->execute();

    
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    
                return $data;

                $this->dbc = null;

            // PDO error handling
        
    } catch ( PDOException $errMsg ) {

        return $errMsg;
    }   
}