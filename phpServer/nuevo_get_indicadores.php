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

$action = call_user_func( "obtenerIndicadores", $obj );
$response = json_encode($action, JSON_UNESCAPED_UNICODE);
$len  = strlen($response);
Header("Content-Length: {$len}");



die($response);



function obtenerIndicadores($obj){
   
    try {
        $dbc = Database::Conectar();

    
     $mes = $obj->mes;
     $quincena = $obj->quincena;
     $year = $obj->year;
     $defaultQuincena = 0;
      switch($mes){
          case 1:
            if($quincena==1){
                $defaultQuincena="01";
            }else{
                $defaultQuincena="02";
            };
          break;
          case 2:
            if($quincena==1){
                $defaultQuincena="03";
            }else{
                $defaultQuincena="04";
            }
          break;  
          case 3:
            if($quincena==1){
                $defaultQuincena="05";
            }else{
                $defaultQuincena="06";
            }
          break;
          case 4:
            if($quincena==1){
                $defaultQuincena="07";
            }else{
                $defaultQuincena="08";
            } 
        break;  
          case 5:
            if($quincena==1){
                $defaultQuincena="09";
            }else{
                $defaultQuincena="10";
            }
        break;
          case 6:
            if($quincena==1){
                $defaultQuincena="11";
            }else{
                $defaultQuincena="12";
            }  
        break;
          case 7:
            if($quincena==1){
                $defaultQuincena="13";
            }else{
                $defaultQuincena="14";
            }
        break;

          case 8: 
            if($quincena==1){
                $defaultQuincena="15";
            }else{
                $defaultQuincena="16";
            }
        break;
          case 9:
            if($quincena==1){
                $defaultQuincena="17";
            }else{
                $defaultQuincena="18";
            }
        break;
          case 10:  
            if($quincena==1){
                $defaultQuincena="19";
            }else{
                $defaultQuincena="20";
            }
        break;
          case 11:
            if($quincena==1){
                $defaultQuincena="21";
            }else{
                $defaultQuincena="22";
            }
        break;
          case 12: 
            if($quincena==1){
                $defaultQuincena="23";
            }else{
                $defaultQuincena="24";
            }
        break;
      
        default:
        # code...
        $response = array(
            'msg' =>'error',
            'data'=>$defaultQuincena
        );

        return  $response;
        $this->dbc = null;
        break;
};

    //quincena inicial con año
    $quincenaIncial= $year.$defaultQuincena;
    //quincena final con año
    $quincenaFinal=$year."24";
    //Obtenemos las quincenas del año especificado
    //$sql = "SELECT qna_pago FROM indicador WHERE qna_pago BETWEEN '$quincenaIncial' AND '$quincenaFinal' GROUP BY qna_pago";
    $sql = "SELECT mes,qna_pago,SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin GROUP BY mes ASC";

   //$sql = "SELECT  SUM(importe) AS total FROM indicador WHERE qna_pago = '$string_query'";

    $stmt = $dbc->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $count=count($data);
    //$array=(object)[]; 
    for ($i=0; $i < $count; $i++) { 
        # code...
        //sumamos los totales de las quincenas
        $quin=$data[$i]->mes;
        $sqlTotales= "SELECT mes,qna_pago,SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin WHERE mes = '$quin'";
        $stmtT = $dbc->prepare($sqlTotales);
        $stmtT->execute();
        $dataT = $stmtT->fetchAll(PDO::FETCH_OBJ);
        $arrayImp[$i]=$dataT[0]->total;
        $arrayQn[$i]=$quin;
    };
        $response=array(
            "quincenas" =>$arrayQn,
            "importes" =>$arrayImp
        );
       return $response;
        $this->dbc = null;
        // PDO error handling
    }catch(PDOException $errMsg ) {
        return $errMsg;
    }  
    

}