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

        /*$stmt = $dbc->prepare("
        SELECT * FROM `menu_hijo2` where `menu_hijo1_Id` = ? AND Activado = 1");
        */

        //$stmt->execute( array($obj->menu_hijo1_Id) );

        /* ESTE ES EL FUNCIONAL POR LORENZO MONTEMAYOR 28 AGO 2019
        $stmt = $dbc->prepare("
        SELECT * FROM `menu_hijo1` where `menu_principal_Id` = '$obj->selected' AND Activado = 1");
        $stmt->execute();

        */
        /*
        $stmt = $dbc->prepare("
        SELECT * FROM `reportestito` WHERE fecha >= '$obj->fechaInicio' AND fecha <= '$obj->fechaFin'");
        
        $stmt = $dbc->prepare("
        SELECT * FROM `usuarios`");
        

        $stmt = $dbc->prepare("UPDATE `usuarios` SET nombre='LM' WHERE ID=1");
        

        $stmt = $dbc->prepare("INSERT INTO `usuarios` (nombre, apellido_paterno, apellido_materno, correo, matricula, contraseña, rol, telefono)
VALUES ('Raul', 'Legaspi', '','john@example.com', '123', 'uno', 'alumno','4921953934')");
        */

        if ( empty($obj->nombre ) ) {
            // do stuff
            $data = (object) [
                'result' => 'No se recibió ningún dato'
            ];

            return $data;
        }else{

                $stmt = $dbc->prepare("INSERT INTO `problemas` (nombre, descripcion, simulaciones, muestra, porcentaje, numeroObjetos, estaActivo, nivelDelProblema, idObjeto)
                VALUES ('$obj->nombre', '$obj->descripcion', '$obj->simulaciones','$obj->muestra', '$obj->porcentaje', '$obj->numeroObjetos', '$obj->estaActivo','$obj->nivelDelProblema', '')");



                $stmt->execute();

    
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);

 
                $usuarioGuardado = (object) [
                    'result' => 'Se Registró el Problema',
                    'dataQuery' => $data
                ];
    
                return $usuarioGuardado;
            
            
                //return $data;

                $this->dbc = null;

            

            
            
            // PDO error handling
        }
    } catch ( PDOException $errMsg ) {

        return $errMsg;
    }   
}