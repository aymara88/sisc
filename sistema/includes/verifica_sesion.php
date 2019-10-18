<?php
    session_start();
    if(!$_SESSION['id_usuario']){
        header('location: ../index.php');
    } 	
	else if(isset($_SESSION['start'])) {
		require ('./conexion.php');	
		$iniciar_sesion = mysqli_query($conection, "UPDATE usuarios SET activo=1 WHERE id_usuario='{$_SESSION['id_usuario']}'");
		$mi_sesion = $_SESSION['start']; 
		$mins = 15;
		$tiempo_actual = time(); 
		$tiempo_transcurrido = $tiempo_actual - $mi_sesion;  
        
        //Se verifica la sesión del usuario, si no ha tenido interacción con el sistema por más de 15 minutos, 
        //se cierra la sesión y el usuario debe iniciar sesión nuevamente
		 if($tiempo_transcurrido >= (60*$mins)) { 
			$cerrar_sesion = mysqli_query($conection, "UPDATE usuarios SET activo=0 WHERE id_usuario='{$_SESSION['id_usuario']}'");
			session_destroy();
			header('location: ../login.php');
		}else { 
			$_SESSION['start'] = $tiempo_actual; 
		}
	}
