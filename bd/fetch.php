<?php

//fetch.php

include('database_connection.php');

if(isset($_POST["id"]))
{
 $query = "
 SELECT mes,qna_pago,SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin
 WHERE SUBSTRING(qna_pago,1,4) = '".$_POST["id"]."' 
 GROUP BY mes ASC
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'concepto'   => $row["mes"],
   'importe'  => floatval($row["total"])
  );
 }
 echo json_encode($output);
}

?>