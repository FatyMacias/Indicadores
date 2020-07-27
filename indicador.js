function data_indicador(){
    var data = { 
        year:2019, 
        mes: 10,
        quincena: 1
    };
    $.ajax({
        url: "http://localhost/indicadores2/phpserver/get_indicadores.php",
        dataType: 'json',
        type: 'post',
        contenType: 'application/json', 
        data:JSON.stringify(data),
        processData:false
      }).done(function(data){
        console.log(data); 
        paintChar(data);
      });
};

function paintChar(dataGet){

    var labels=dataGet.quincenas;
    var dataLabels=dataGet.importes;
    
    var data= {
        labels: labels,
        datasets: [{
            label: 'Indicadores de quincenas del año 2020',
            data: dataLabels,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    };

    var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

}

data_indicador();

function selectCambia(){
    var select=document.getElementById('year');

    console.log(select.value);
    data_indicador(select.value)

}


