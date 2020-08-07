<?php

//fetch.php
//SELECT mes,concepto,SUBSTRING(qna_pago,1,4),SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin WHERE concepto = '01'AND mes = '1' AND SUBSTRING(qna_pago,1,4) = '2020' GROUP BY mes ASC
include('database_connection.php');

if(isset($_POST["idc"]) && isset($_POST["idm"]) )
{
 $query = "
 SELECT mes,concepto,SUBSTRING(qna_pago,1,4),SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin
 WHERE concepto = '".$_POST["idc"]."' AND mes = '".$_POST["idm"]."'
 GROUP BY SUBSTRING(qna_pago,1,4) ASC 
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();


 foreach($result as $row)
 {
  $output[] = array(
   'concepto'   => $row["SUBSTRING(qna_pago,1,4)"],
   'importe'  => floatval($row["total"])
  );
 }
 echo json_encode($output);
}

?>