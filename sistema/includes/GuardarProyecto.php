<?php
	require ('../conexion.php');
	$id_proyecto = (int)$_GET['id'];
	$descripcion = mysqli_real_escape_string($conection, $_GET['descripcion']);	
	$cliente = mysqli_real_escape_string($conection, $_GET['cliente']);

	if($id_proyecto == 0)
		exit;
	$query_proyecto = mysqli_query($conection,"SELECT * FROM obras 
												WHERE id_proyecto = '$id_proyecto' 
												LIMIT 1
								");
	$resultado_proyecto = mysqli_num_rows($query_proyecto);
	if($resultado_proyecto == 1){
		$update_query = mysqli_query($conection,"UPDATE obras SET descripcion_proyecto='$descripcion',
													id_cliente='$cliente'
												WHERE id_proyecto='$id_proyecto'
								");
		echo "Proyecto Actualizado Correctamente!";
	}
	else{
		echo "No se puede actualizar el proyecto!";
	}
?>		


