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


            if (isset($obj->imgPadre)) {
                # code...
                $nameP=$obj->nombre;
                $file = $obj->imgPadre->base64;
                $name=$obj->imgPadre->filename;
                $img= base64_decode($file);
                $path= 'public/'.$nameP.'/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    file_put_contents($path.$name,$img);
                }else{
                    file_put_contents($path.$name,$img);
                };
                $rutaP=$path.$name;
            }else{
                $rutaP=$obj->url_imagen_padre;
            }

            $stmt = $dbc->prepare("UPDATE `objetos` SET nombre='$obj->nombre', descripcion='$obj->descripcion', esta_activo='$obj->estaActivo', url_imagen_padre='$rutaP', tipo_objeto='$obj->simple'
                WHERE ID='$obj->ID'"
            );

            if (!$stmt->execute()) {
                $error = (object) [
                    'result' => 'error',
                    'data' => $data
                ];
            return $error;
            $this->dbc = null;
        }else{
            //Removemos los objetos hijos (cambio status)
            $idsRemoves=$obj->removes;
            $countRem=count($idsRemoves);
            for ($r=0; $r <$countRem; $r++) { 
                # code...
                $idR=$idsRemoves[$r];
                $newI = $dbc->prepare(
                    "UPDATE `objetos_lista` 
                    SET esta_activo=0
                    WHERE ID='$idR'");

                if (!$newI->execute()) {
                    $error = (object) [
                        'result' => 'error',
                        'data' => $data
                    ];

                    return $error;
                    $this->dbc = null;
                }else{

                };
            }
            //Nombre principal y ID objPadre
            $nameP=$obj->nombre;
            $id = $obj->ID;

            $objHijos=$obj->objetosHijos;

            $count=count($objHijos);
            for ($i=0; $i <$count ; $i++) { 
                # code...
                $elemento=$objHijos[$i];
                if ($elemento->idChild==null) {
                    $tag=$elemento->etiqueta;
                    if (isset($elemento->img)) {
                        
                        $fileH =$elemento->img->base64;
                        $nameH=$elemento->img->filename;
                        $imgD= base64_decode($fileH);
                        $pathH= 'public/'.$nameP.'/'.$tag.'/';
                        if (!file_exists($pathH)) {
                            mkdir($pathH, 0777, true);
                            file_put_contents($pathH.$nameH,$imgD);
                        }else{
                            file_put_contents($path.$nameH,$imgD);
                        };
                        $rutaH=$pathH.$nameH;
                    }else{
                        $rutaH='public/todo/not-image.png';
                    }

                    $newI = $dbc->prepare(
                        "INSERT INTO `objetos_lista` (id_relacion,url_img,esta_activo,etiqueta,cantidad)
                        VALUES ('$id','$rutaH','$obj->estaActivo', '$tag','0')");
                    
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
                }else{

                    $idCh=$elemento->idChild;
                    $tag=$elemento->etiqueta;
                    

                    if (isset($elemento->img)) {
                        # code...
                        $fileH =$elemento->img->base64;
                        $nameH=$elemento->img->filename;
                        $imgD= base64_decode($fileH);
                        $pathH= 'public/'.$nameP.'/'.$tag.'/';
                        if (!file_exists($pathH)) {
                            mkdir($pathH, 0777, true);
                            file_put_contents($pathH.$nameH,$imgD);
                        }else{
                            file_put_contents($pathH.$nameH,$imgD);
                        };
                        $rutaH=$pathH.$nameH;
                        $newI = $dbc->prepare(
                            "UPDATE `objetos_lista` 
                            SET id_relacion='$id',url_img='$rutaH',esta_activo='$obj->estaActivo',etiqueta='$tag',cantidad='0'
                            WHERE ID='$idCh'");
                    
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
                    }else{
                        $rutaH=$elemento->urlChild;
                        $newI = $dbc->prepare(
                            "UPDATE `objetos_lista` 
                            SET id_relacion='$id',url_img='$rutaH',esta_activo='$obj->estaActivo',etiqueta='$tag',cantidad='0'
                            WHERE ID='$idCh'");
                    
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

                    }
                }
            };
            //objetoHijo($id,$obj);
            $objetoGuardado = (object) [
                    'result' => 'ok',
                    'data' => 'Editados'
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