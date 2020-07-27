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

$action = call_user_func( "saveProblema", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function saveProblema($obj){
    try {
        $dbc = Database::Conectar();
        if (empty($obj->nombreP) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{
            $stmt = $dbc->prepare("INSERT INTO `problemas` (nombre, descripcion, simulaciones, porcentaje, numeroObjetos, estaActivo, nivelDelProblema,mundo,respuestaT,status)
                VALUES ('$obj->nombreP', '$obj->descripcion', '$obj->simulaciones', '$obj->porcentaje', '$obj->numeroObjetos', '$obj->estaActivo','1','$obj->mundo','$obj->respuestaT','1')");
            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
            return $error;
            $this->dbc = null;
        }else{
            //$stmt->execute();
            //
            $id = $dbc->lastInsertId();
            //$this->dbc = null;
            $objetos=$obj->idObjeto;
            //echo $obj;
            $count=count($objetos);
            //
            $objetosP=$obj->objetosPadre;
            $countOP=count($objetosP);
            for ($i=0; $i <$count ; $i++) { 
                # code...
                $objeto=$objetos[$i]->idChild;
                $cantidad=$objetos[$i]->cantidad;
                $tag=$objetos[$i]->tagExtra;
                $newI = $dbc->prepare("INSERT INTO `objetosproblemas` (id_objetos_lista,id_problemas,cantidad,etiqueta_problema)
                VALUES ('$objeto','$id','$cantidad','$tag')");
                if (!$newI->execute()) {
                    $error = (object) [
                        'result' => 'error',
                        'data' => $data
                    ];
                return $error;
                    $this->dbc = null;
                }else{
                };
            };

            for ($j=0; $j <$countOP ; $j++) { 
                # code...
                $objeto=$objetosP[$j]->idPadre;
                $muestra=$objetosP[$j]->muestra;
                $repeticion=$objetosP[$j]->repeticion;
                $newI = $dbc->prepare("INSERT INTO `muestra` (id_objeto_padre,id_problema,muestra,repeticion)
                VALUES ('$objeto','$id','$muestra','$repeticion')");

                if (!$newI->execute()) {
                    $error = (object) [
                        'result' => 'error',
                        'data' => $data
                    ];
                    return $error;
                    $this->dbc = null;
                }else{
                };
            };
            //objetoHijo($id,$obj);
           $problemaGuardado = (object) [
                    'result' => 'ok',
                    'data' => 'Guardado'
                ];
            return  $problemaGuardado;
            $this->dbc = null;
        };
    }
} catch ( PDOException $errMsg ) {
        $error = (object) [
                    'result' => 'error',
                    'data' => $errMsg
                ];
        return $error;
    }   
};