<?php  

//index.php
/*
jeje, me tarde un shingo pero al fin pude generar una grafica dynamica que actualize la info de la grafica sin tener que refrescar la pagina, solo hay que realizar una sellecion  en este caso por id te saca la grafica de el importe de cada concepto individualmente, jaja olv


*/
include("database_connection.php");

$query = "SELECT SUBSTRING(qna_pago,1,4) AS 'year' FROM indicador GROUP BY year DESC";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

?>  
<!DOCTYPE html>  
<html>  
    <head>  
        <title>la mera mera</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
    </head>  
    <body> 
        <br /><br />
        <div class="container">  
            <h3 align="center">Grafica Dinamica</h3>  
            <br />  
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 class="panel-title">Importes por Quincena</h3>
                        </div>
                        <div class="col-md-3">
                            <select name="id" class="form-control" id="id">
                                <option value="">Selecciona id</option>
                            <?php
                            foreach($result as $row)
                            {
                                echo '<option value="'.$row["year"].'">'.$row["year"].'</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="chart_area" style="width: 1000px; height: 620px;"></div>
                </div>
            </div>
        </div>  
    </body>  
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback();

function load_conceptowise_data(id, title)
{
    var temp_title = title + ' '+id+'';
    $.ajax({
        url:"fetch.php",
        method:"POST",
        data:{id:id},
        dataType:"JSON",
        success:function(data)
        {
            drawMonthwiseChart(data, temp_title);
        }
    });
}

function drawMonthwiseChart(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Quincenas');
    data.addColumn('number', 'Importe');
    $.each(jsonData, function(i, jsonData){
        var concepto = jsonData.concepto;
        var importe = parseFloat($.trim(jsonData.importe));
        data.addRows([[concepto, importe]]);
    });
    var options = {
        title:chart_main_title,
        hAxis: {
            title: "Quincenas"
        },
        vAxis: {
            title: 'Importe'
        }
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
    chart.draw(data, options);
}

</script>

<script>
    
$(document).ready(function(){

    $('#id').change(function(){
        var id = $(this).val();
        if(id != '')
        {
            load_conceptowise_data(id, 'Concepto Por Cada Importe, Quincenas del: ');
        }
    });

});

</script>