<?php  

//index.php
/*
jeje, me tarde un shingo pero al fin pude generar una grafica dynamica que actualize la info de la grafica sin tener que refrescar la pagina, solo hay que realizar una sellecion  en este caso por id te saca la grafica de el importe de cada concepto individualmente, jaja olv


*/
include("bd/database_connection.php");

$query = "SELECT SUBSTRING(qna_pago,1,4) AS 'year' FROM indicador GROUP BY year ASC";
$queryC = "SELECT cve_cpto, concepto FROM `cat_conceptos`";
//$query = "SELECT SUBSTRING(qna_pago,1,4) AS 'year' FROM indicador GROUP BY year DESC";
$queryM = "SELECT mes,id_mes,nombre FROM `cat_mes` JOIN nom_mes ON cat_mes.mes = nom_mes.id_mes GROUP BY mes ORDER BY id_quin";


$statement = $connect->prepare($query);
$statementC = $connect->prepare($queryC);
$statementM = $connect->prepare($queryM);

$statement->execute();
$statementC->execute();
$statementM->execute();

$result = $statement->fetchAll();
$resultC = $statementC->fetchAll();
$resultM = $statementM->fetchAll();

?>  

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>INDICADORES</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
    
  </head>
  <body>
    
    <div class="wrapper d-flex align-items-stretch">
      <nav id="sidebar">
      <center><a class="navbar-brand" href="#">INDICADORES</a></center>  
        <div class="p-4 pt-5">
          <a href="#" class="img logo thumbnailmb-5" style="background-image: url(images/zac.png);"></a>
          <ul class="list-unstyled components mb-5">


            <li class="active">
              <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
              <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="#" onclick="openMenu('general')">General</a>
                </li>
                <li>
                    <a href="#" onclick="openMenu('conceptos')">Por concepto</a>
                </li>
                <li>
                    <a href="#">Por banco</a>
                </li>
                <li>
                    <a href="#">Por genero</a>
                </li>
              </ul>
            </li>


            <li>
                <a href="#">About</a>
            </li>
            <li>
              <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
              <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
              </ul>
            </li>
            <li>
              <a href="#">Portfolio</a>
            </li>
            <li>
              <a href="#">Contact</a>
            </li>
          </ul>

          <div class="footer">
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib.com</a>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>

        </div>
      </nav>
      

      

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <!-- Modal -->
   <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">Modal</h4>
        </div>
        <div class="modal-body" id="body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <div id="general" class="w3-container menu">
          <center><h1>Indicadores</h1></center>
          <div>
      
              
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

          <div class="panel-body">
           
              <div style="width: 200px; height: 10px;"></div>
            
          </div>
          <div class="panel-body">
            <table class="table table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Mes</th>
                  <th scope="col">Importe</th>
                </tr>
              </thead>
              <tbody  id="col1">

              </tbody>
            </table>
           
              <div id="chart_area" style="width: 1200px; height: 500px;"></div>
              
            
          </div>
          
          
          <div class="panel-body">
            
              <div id="chart_area2" style="width: 1200px; height: 500px;"></div>
              
          </div>
          
          </div>


          <div id="conceptos" class="w3-container menu" style="display:none">
            <center><h1>Por Conceptos</h1></center>
          <div>
      
              
                <select name="idc" class="form-control" id="idc">
                            <option value="">Seleccionar Concepto</option>
                            <?php
                            foreach($resultC as $row)
                            {
                                echo '<option value="'.$row["cve_cpto"].'">'.$row["concepto"].', codigo: '.$row["cve_cpto"].'</option>';
                            }
                            ?>
                </select>

                
                <select name="idm" class="form-control" id="idm">
                            <option value="">Seleccionar Mes</option>
                            <?php
                            foreach($resultM as $row)
                            {
                                echo '<option value="'.$row["mes"].'">'.$row["nombre"].'</option>';
                            }
                            ?>
                </select>
          </div>
          <div class="panel-body">
           
              <div style="width: 200px; height: 10px;"></div>
            
          </div>
          <div class="panel-body">
           
              <div id="chart_area3" style="width: 1200px; height: 500px;"></div>
            
          </div>
          </div>
          <script>
            function openMenu(menuName) {
              var i;
              var x = document.getElementsByClassName("menu");
              for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";  
                  }
              document.getElementById(menuName).style.display = "block";  
            }
          </script>
          
          

        <!-- <h2 class="mb-4">Sidebar #01</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
    </div> -->
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

    
    
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>

<script type="text/javascript" src="./charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.load('current', {'packages':['table']});
//google.charts.load('current', {packages: ['table']});
google.charts.setOnLoadCallback();


// peticion a la base de datos grafica 1
function load_conceptowise_data(id, title)
{
    var temp_title = title + ' '+id+'';
    $.ajax({
        url:"bd/fetch.php",
        method:"POST",
        data:{id:id},
        dataType:"JSON",
        success:function(data)
        {
            drawMonthwiseChart(data, temp_title);
            
        },
        error: function(data)
        {
            alert("No hay Datos");
        }
    });
}
// peticion a la base de datos grafica 2
function load_conceptowise2_data(id, title)
{
    var temp_title = title + ' '+id+'';
    $.ajax({
        url:"bd/nuevo_fetch.php",
        method:"POST",
        data:{id:id},
        dataType:"JSON",
        success:function(data)
        {
            drawMonthwiseChart2(data, temp_title);
        },
        error: function(data)
        {
            alert("No hay Datos");
        }
    });
}
////////////////////////////
function load_conceptowise3_data(idc, idm, title)
{
    var temp_title = title + ' '+idc+''+' '+'del mes'+' '+idm+'';
    $.ajax({
        url:"bd/fetch_concepto.php",
        method:"POST",
        data:{idc:idc, idm:idm},
        dataType:"JSON",
        success:function(data)
        {
            drawMonthwiseChart3(data, temp_title);
        },
        error: function(data)
        {
            alert("No hay Datos");
        }
    });
}

// dibujar grafica 1
function drawMonthwiseChart(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    //
    var tablaData ='';
    //
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Quincenas');
    data.addColumn('number', 'Importe $');
    data.addColumn({
               type: 'string',
               role: 'style'
           });

   $('#col1').empty();
    $.each(jsonData, function(i, jsonData){
        var concepto = jsonData.concepto;
        var importe = parseFloat($.trim(jsonData.importe));
        var style = jsonData.style;
        data.addRows([[concepto, importe, style]]);
        /////////
        tablaData += '<tr>';
        tablaData += '<td>'+jsonData.concepto+'</td>';
        tablaData += '<td>'+jsonData.importe+'</td>';
        tablaData += '</tr>';
        /////////

    });

   var axis = data.getNumberOfRows();
   //alert('max data table value: ' + data.getValue(0, 0));
   for(var x=0;x<axis;x++){
    data.setValue(x, 2, '#'+Math.floor(Math.random()*16777215).toString(16));
    //data.getValue(0,0)

   }

 $("#col1").append(tablaData);



    var options = {
        title:chart_main_title, 
        legend: 'none',
        hAxis: {
            title: "Quincenas"
        },
        vAxis: {
            title: 'Importe',
            format: 'currency'
        },
        chartArea: {
        backgroundColor: {
        stroke: '#008f39',
        strokeWidth: 3
    }
}

    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
    //var visualization = new google.visualization.Table(document.getElementById('chart_areas'));
    chart.draw(data, options);
    //visualization.draw(data);

    google.visualization.events.addListener(chart, 'select', selectHandler);

    function selectHandler() {
      var selection = chart.getSelection()[0];
      var selectedValue = data.getValue(selection.row, 0);
      var selectedImporte = data.getValue(selection.row, 1);
    alert('Seleccionaste el Mes:' + ' ' + selectedValue + ' ' +'con un Importe de:'+ ' ' + selectedImporte);
    $("#body").html('Seleccionaste el Mes:' + ' ' + selectedValue + ' ' +'con un Importe de:'+ ' ' + selectedImporte);
    $("#myModal").modal();
}
}
/////tabla



// dibujar grafica 2
function drawMonthwiseChart2(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Quincenas');
    data.addColumn('number', 'Importe $');
    data.addColumn({
               type: 'string',
               role: 'style'
           });
    $.each(jsonData, function(i, jsonData){
        var concepto = jsonData.concepto;
        var importe = parseFloat($.trim(jsonData.importe));
        var style = jsonData.style;
        data.addRows([[concepto, importe, style]]);


    });
    var axis = data.getNumberOfRows();
    //alert('max data table value: ' + axis.max);
    for(var x=0;x<axis;x++){
    data.setValue(x, 2, '#'+Math.floor(Math.random()*16777215).toString(16));
   }
    

    var options = {
        title:chart_main_title,
        legend: 'none',
        hAxis: {
            title: "Quincenas"
        },
        vAxis: {
            title: 'Importe',
            format: 'currency'
        },
        chartArea: {
        backgroundColor: {
        stroke: '#008f39',
        strokeWidth: 3
    }
}

    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_area2'));
    chart.draw(data, options);
}

function drawMonthwiseChart3(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Quincenas');
    data.addColumn('number', 'Importe $');
    data.addColumn({
               type: 'string',
               role: 'style'
           });
    $.each(jsonData, function(i, jsonData){
        var concepto = jsonData.concepto;
        var importe = parseFloat($.trim(jsonData.importe));
        var style = jsonData.style;
        data.addRows([[concepto, importe, style]]);


    });
    var axis = data.getNumberOfRows();
    //alert('max data table value: ' + axis);
    for(var x=0;x<axis;x++){
    data.setValue(x, 2, '#'+Math.floor(Math.random()*16777215).toString(16));
   }
    /*
    for(var x=0;x<axis.max;x++){
    data.setValue(x, 2, '#'+Math.floor(Math.random()*16777215).toString(16));
   }
   */
    var options = {
        title:chart_main_title,
        legend: 'none',
        hAxis: {
            title: "Quincenas"
        },
        vAxis: {
            title: 'Importe',
            format: 'currency'
        },
        chartArea: {
        backgroundColor: {
        stroke: '#008f39',
        strokeWidth: 3
    }
}

    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_area3'));
    chart.draw(data, options);
}
</script>


<script>
    // Detectar seleccion del select option
$(document).ready(function(){

    $('#id').change(function(){
        var id = $(this).val();
        if(id != '')
        {
            //alert("The text has been changed.");
            load_conceptowise_data(id, 'Importe Por Cada Mes, Quincenas del: ');
            load_conceptowise2_data(id, 'Importe Por Cada Quincena, Quincenas del: ');
        }
    });

});

</script>

<script>
    // Detectar seleccion del select option
$(document).ready(function(){

    $('#idc, #idm').change(function(){
        var idc = $('#idc').val();
        var idm = $('#idm').val();
        if(idc != '' && idm != '')
        {
            //alert("The text has been changed.");
            
            load_conceptowise3_data(idc, idm, 'Importe Por Cada Year, Concepto: ');
        }
    });

});

</script>

