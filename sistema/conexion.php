<?php

    //Datos de conexion Servidor Ocamcas
   /* $host = 'localhost';
    $user ='constr72_gama';
    $password ='teloloapan';
    $db ='constr72_constructora';  */ 


    //Datos del servidor local_casa
    $host = 'localhost';
    $user ='root';
    $password ='';
    $db ='constructora';  

    $conection = @mysqli_connect($host,$user,$password,$db);
    mysqli_set_charset($conection, "utf8");

    if(!$conection){
    	echo "Error en la conexion";
	} 

?>