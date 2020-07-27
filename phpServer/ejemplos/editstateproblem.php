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
            $stmt = $dbc->prepare("UPDATE `problemas` SET nombre='$obj->nombreP',descripcion='$obj->descripcion', simulaciones='$obj->simulaciones', muestra='$obj->muestra', porcentaje='$obj->porcentaje', numeroObjetos='$obj->numeroObjetos', estaActivo='$obj->estaActivo', nivelDelProblema='$obj->nivelDelProblema',mundo='$obj->mundo',repeticion='$obj->repeticion',respuestaT='$obj->respuestaT'
                WHERE ID='$obj->idProblem'");
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
            $id = $obj->idProblem;
            //$this->dbc = null;
            $stmtD = $dbc->prepare(
                "DELETE FROM `objetosproblemas` WHERE id_problemas='$obj->idProblem'");
            if (!$stmtD->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                    ];
                return $error;
                $this->dbc = null;
            }else{
            $objetos=$obj->idObjeto;
            //echo $obj;
            $count=count($objetos);
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
                     //$this->dbc = null;
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
        };
    };
} catch ( PDOException $errMsg ) {
        $error = (object) [
                    'result' => 'error',
                    'data' => $errMsg
                ];
        return $error;
    }   
};