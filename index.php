<?php
//magia negra para la persisitencia de datos almacenados en los inputs
$clav = $cla = '';
$hijole='';
// array de errores
$errores = array('clave_user'=>'','clave_pwd'=>'');
$conexion = mysqli_connect('localhost:3306', 'root', '', 'indicadores');

if(isset($_POST['submit'])){

	//checar usuario
	if(empty($_POST['clave_user'])){
		$errores['clave_user'] ='Tu Clave de Usuario es Requerida';
	} else{
		//echo htmlspecialchars($_POST['clave_user']);
		$clav = $_POST["clave_user"];
		if (!preg_match('/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/', $clav)) {
			$errores['clave_user'] ='Nombre de usuario solo debe tener letras y números.';
		}
	}
	//checar password
	if(empty($_POST['clave_pwd'])){
		$errores['clave_pwd'] ='Tu Clave de Usuario es Requerida';
	} else{
		//echo htmlspecialchars($_POST['clave_pwd']);
		$cla = $_POST["clave_pwd"];
		if (!preg_match('/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/', $cla)) {
			$errores['clave_pwd'] ='La contraseña es incorrecta, inténtelo de  nuevo.';
		}
	}

		if(array_filter($errores)){
			//echo "hay errores";
		}else{

			$clav = mysqli_real_escape_string($conexion,$_POST['clave_user']);
			$cla = mysqli_real_escape_string($conexion,$_POST['clave_pwd']);

			$consulta = "SELECT * FROM usuarios WHERE clave_user = '$clav' AND clave_pwd = '$cla'";
			$resultado = mysqli_query ($conexion, $consulta);
			$filas = mysqli_num_rows ($resultado); 

			if ($filas>0){
       			header("location: inicio.php"); 

			}
			else{
       			$hijole='Usuario no autorizado';
			}

			mysqli_free_result($resultado);
			mysqli_close($conexion);
			//$clav = mysqli_real_escape_string($)
		}

}


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Inidcadores</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/gob.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/zalogo.png);">
					<span class="login100-form-title-1">
						Iniciar Sesión
					</span>
				</div>
				<!--===============================================================================================-->	
				<form action ="index.php" method= "POST" class="login100-form validate-form">
				<!--===============================================================================================-->	
			
				<div class="wrap-input100 validate-input m-b-26" data-validate="Tu usuario es requerido">
						<span class="label-input100">Usuario</span>
						<input class="input100" type="text" name="clave_user" value="<?php echo htmlspecialchars($clav) ?>" placeholder="Ingresa tu usuario">
						<span class="focus-input100"></span>
					</div>
					<div class="red-text" style="color:red"> <?php echo $errores['clave_user']; ?></div>
<!--===============================================================================================-->
					<div class="wrap-input100 validate-input m-b-18" data-validate = "Se requiere tu contraseña">
						<span class="label-input100">Contraseña</span>
						<input class="input100" type="password" name="clave_pwd" value="<?php echo htmlspecialchars($cla) ?>" placeholder="Ingresa tu contraseña">
						
						<span class="focus-input100"></span>
					</div>
					<div class="red-text" style="color:red"> <?php echo $errores['clave_pwd']; ?></div>

				<!-- 	<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="#" class="txt1">
								Forgot Password?
							</a>
						</div>
					</div> -->

					<div class="container-login100-form-btn justify-content-center">
						<Input class="login100-form-btn"  type="submit" name="submit" value="Ingresar">
							
					</div>
					<div class="red-text	"  style="color:red"> <?php echo $hijole; ?></div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>