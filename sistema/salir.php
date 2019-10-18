<?php 
	session_start();
    if(!$_SESSION['id_usuario']){
        header('location: ../index.php');
    } 	
	else if(isset($_SESSION['start'])) {
		require ('./conexion.php');	
        $destruir_sesion = mysqli_query($conection, "UPDATE usuarios SET activo=0 WHERE id_usuario='{$_SESSION['id_usuario']}'");
        session_destroy();
        header('location: ../');
	}
 ?>