<?php 
    function getConnection(){

      //Datos de conexión Servidor Ocamcas
      /*  $servername = 'localhost';
        $username ='constr72_gama';
        $password ='teloloapan';
        $database ='constr72_constructora'; */
        
        //Datos del servidor de pruebas-local
        $servername = "localhost";
        $username = "root";
        $password ="";
        $database ="constructora"; 

         try {
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           // echo "Connected successfully"; 
            return $conn;
        } catch(PDOException $e) {    
            echo "Connection failed: " . $e->getMessage();
        }
    }
?>