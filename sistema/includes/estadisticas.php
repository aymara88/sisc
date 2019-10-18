<?php
	require ('./conexion.php');
	global $estadisticas;
	/* Traemos los datos de los 5 procedimientos llamando a cada uno de ellos para ahorrar Consultas 
		para poder traer más de un procedimiento a la vez necesitamos agregar la función next result*/
	$clientesq = mysqli_query($conection, "CALL CLIENTES(1)");
	$clientesr = mysqli_fetch_assoc($clientesq);
	mysqli_next_result($conection); 

	$usuariosq = mysqli_query($conection, "CALL USUARIOS(1)");
	$usuariosr = mysqli_fetch_array($usuariosq);
	mysqli_next_result($conection); 
	
	$proveedoresq = mysqli_query($conection, "CALL PROVEEDORES(1)");
	$proveedoresr = mysqli_fetch_array($proveedoresq);
	mysqli_next_result($conection); 
	
	$proyectosq = mysqli_query($conection, "CALL PROYECTOS(1)");
	$proyectosr = mysqli_fetch_array($proyectosq);
	mysqli_next_result($conection); 
	
	$reportesq = mysqli_query($conection, "CALL REPORTES(1)");
	$reportesr = mysqli_fetch_array($reportesq);
	mysqli_next_result($conection); 
	
	/* Almacenamos todos los datos en un arreglo para mandarlos al íncide del foro en la parte de estadísticas */
	$estadisticas = array(
		"clientes" => $clientesr,
		"usuarios" => $usuariosr,
		"proveedores" => $proveedoresr,
		"proyectos" => $proyectosr,
		"reportes" => $reportesr
	);
	return $estadisticas;
	mysql_close($conection);
?>