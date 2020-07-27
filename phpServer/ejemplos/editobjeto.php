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

$action = call_user_func( "saveObjeto", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function saveObjeto($obj){
    try {
        $dbc = Database::Conectar();
        if (empty($obj->nombre ) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{
            $nameP=$obj->nombre;
            $file = $obj->imgPadre->base64;
            $name=$obj->imgPadre->filename;
            $img= base64_decode($file);
            $path= 'public/'.$nameP.'/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                file_put_contents($path.$name,$img);
            };
            $rutaP=$path.$name;
            $stmt = $dbc->prepare("UPDATE `objetos` SET nombre='$obj->nombre', descripcion='$obj->descripcion', esta_activo='$obj->estaActivo', url_imagen_padre='$rutaP', tipo_objeto='$obj->simple'
                WHERE ID='$obj->idProblem'"
            );
            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
            return $error;
            $this->dbc = null;
        }else{
            $nameP=$obj->nombre;
            $id = $dbc->lastInsertId();
            $etiqueta=$obj->tags;
            $img=$obj->img;
            $count=count($etiqueta);
            for ($i=0; $i <$count ; $i++) { 
                # code...
                $tag=$etiqueta[$i];
                $fileH = $img->$i->base64;
                $nameH=$img->$i->filename;
                $imgD= base64_decode($fileH);
                $pathH= 'public/'.$nameP.'/'.$tag.'/';
                if (!file_exists($pathH)) {
                    mkdir($pathH, 0777, true);
                    file_put_contents($pathH.$nameH,$imgD);
                };
                $rutaH=$pathH.$nameH;
                $newI = $dbc->prepare("UPDATE `objetos_lista` SET id_relacion='$id',url_img='$rutaH',esta_activo='$obj->estaActivo',etiqueta='$tag',cantidad='0'");
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
           $objetoGuardado = (object) [
                    'result' => 'ok',
                    'data' => 'Guardados'
                ];
            return $objetoGuardado;
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