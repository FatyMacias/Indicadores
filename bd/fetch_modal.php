<?php
// SELECT mes,cve_cpto,cat_conceptos.concepto AS nomcom,per_ded,qna_pago,SUM(importe) AS 'total' FROM indicador JOIN cat_mes ON indicador.qna_pago = cat_mes.id_quin JOIN cat_conceptos ON indicador.concepto = cat_conceptos.cve_cpto 
// WHERE SUBSTRING(qna_pago,1,4) = '".$_POST["id"]."'
// GROUP BY mes ORDER BY qna_pago ASC
//SELECT cve_cpto,cat_conceptos.concepto AS nomcom,per_ded,qna_pago,SUM(importe) AS 'total' FROM indicador JOIN cat_conceptos ON indicador.concepto = cat_conceptos.cve_cpto 
// WHERE SUBSTRING(qna_pago,1,4) = '2016' group BY cve_cpto

include('database_connection.php');

if(isset($_POST["id"]))
{
 $query = "
 
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();

 
 foreach($result as $row)
 {
  $output[] = array(
   
    'clave'   => $row["cve_cpto"],
    'nombre'  => $row["nomcom"],
    'deduc'   => $row["per_ded"],
   'importe'  => floatval($row["total"])
  );
 }
 echo json_encode($output);
}

?>