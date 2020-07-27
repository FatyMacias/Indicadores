<?php

         $dbhost = 'localhost:3306';
         $dbuser = 'root';
         $dbpass = '';
         $dbnombre  = 'indicadores';
         $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
         $db = mysqli_select_db( $conn, $dbnombre );
      
         if(! $conn ) {

            die('No se realizo la conexión ' . mysql_error());
         }
         
         

         echo 'Conexión exitosa';
         mysqli_close($conn);
      ?>