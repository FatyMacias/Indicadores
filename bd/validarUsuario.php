<?php
session_start();

//aqui se obtienen los datos que estamos ingresados, previamente cargados en mi formulario
$clave = $_POST['clave_user'];
$password = $_POST['clave_pwd'];

         // require_once 'conexion.php';
        //include ("conexion.php");
       // $conn = dbConnect();
      //echo"si hay datos, $nombre";

$conexion = mysqli_connect('localhost:3306', 'root', '', 'indicadores');
$consulta = "SELECT * FROM usuarios WHERE clave_user = '$clave' AND clave_pwd = '$password'";
$resultado = mysqli_query ($conexion, $consulta);

//validacion de los datos 
$filas = mysqli_num_rows ($resultado); 

if ($filas>0){
       header("location:bienvenidos.html"); 

}
else{
       echo"Error en la autenficacion";
}

mysqli_free_result($resultado);
mysqli_close($conexion);



/* $x ="SELECT * FROM usuarios WHERE clave_user = '$clave' AND clave_pwd = '$password'";
echo $x;
$conecta= new mysqli('localhost:3306','root','','indicadores');
$consulta =mysqli_query ($conecta,$x); 


// aqui se hace la validación de nuestros datos 
if(!$consulta){ 
 echo "Usuario no existe " . $clave . " " . $password. " o hubo un error " . mysqli_error($mysqli);
} 
else{ 
print "Bienvenido"; 
} 
 */
?>