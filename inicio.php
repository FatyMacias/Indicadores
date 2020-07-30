<!doctype html>
<html lang="en">
  <head>
  	<title>INDICADORES</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
  </head>
  <body>
		
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
      <center><a class="navbar-brand" href="#">INDICADORES</a></center>  
				<div class="p-4 pt-5">
		  		<a href="#" class="img logo thumbnailmb-5" style="background-image: url(images/zac.png);"></a>
	        <ul class="list-unstyled components mb-5">
	          <li class="active">
              <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Global</a>
              
	            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="#">Por concepto</a>
                </li>
                <li>
                    <a href="#">Por banco</a>
                </li>
                <li>
                    <a href="#">Por genero</a>
                </li>
	            </ul>
	          </li>
	         <!--  <li>
	              <a href="#">About</a>
	          </li> -->
	          <li>
              <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Por Subsistema</a>
              <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Básica</a>
                </li>
                <li>
                    <a href="#">Telesecundaria</a>
                </li>
                <li>
                    <a href="#">Telebachillerato</a>
                </li>
              </ul>
	          </li>
	        <!--   <li>
              <a href="#">Portfolio</a>
	          </li>
	          <li>
              <a href="#">Contact</a>
	          </li> -->
	        </ul>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
	        <div class="footer text-center py-3">
            
	        	<p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						  Derechos Reservados &copy;<script>document.write(new Date().getFullYear());</script>    Secretaría de Educación del estado de Zacatecas.<i class="icon-heart" aria-hidden="true"></i>  <a href="http://www.seduzac.gob.mx/portal/" target="_blank">Seduzac.com</a>
						  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
	        </div>   
	      </div>
      </nav>
      

      

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

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

                    <a class="nav-link" href="inicio.php"> <ion-icon name="home-outline"></ion-icon> Inicio</a>
                </li>
            
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><ion-icon name="exit-outline"></ion-icon>Cerrar Sesión</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <body>
          <center><h1>INDICADORES</h1></center>
          <div>
                   
                <select  class="form-control form-control-sm" name="" style="width: 300px; height: 35px;" id="year" onchange="selectCambia()">
                  <option>Selecciona el año que deseas ver</option>
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
                  <option value="2022">2022</option>
                </select>
          </div>

          
          <div class=""><canvas id="myChart" style="width: 500px; height: 200px;"></canvas></div>


        <!-- <h2 class="mb-4">Sidebar #01</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
		</div> -->
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
    <script src="indicador.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>