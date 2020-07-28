<?php

//fetch.php

include('database_connection.php');

if(isset($_POST["id"]))
{
 $query = "
 SELECT qna_pago,SUM(importe) AS 'total' FROM indicador 
 WHERE SUBSTRING(qna_pago,1,4) = '".$_POST["id"]."' 
 GROUP BY SUBSTRING(qna_pago,5,6) ASC LIMIT 24
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'concepto'   => $row["qna_pago"],
   'importe'  => floatval($row["total"])
  );
 }
 echo json_encode($output);
}

?>