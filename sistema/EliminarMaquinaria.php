<?php
require_once "includes/verifica_sesion.php";

	include "conexion.php";
	$datos = 0;
	$id = (int)$_GET['id'];
	if($id == 0)
	{
		$html = "La maquinaria que se desea eliminar no es correcta";
	}
	else
	{
		$query = mysqli_query($conection,"SELECT * FROM estimacion_maquinaria WHERE estatus=1 AND id_estimacion_maquinaria='$id' LIMIT 1");
		$resultado  = mysqli_num_rows($query);
		if($resultado > 0){
			$update_query = mysqli_query($conection,"UPDATE estimacion_maquinaria SET estatus=0 WHERE id_estimacion_maquinaria='$id'");
			$html = "Maquinaria eliminada correctamente";		
		}
		else{
			$html = "La maquinaria seleccionada no se puede eliminar";
		}
	}
	echo $html;
	header("location: crear_proyecto.php");	
?>		


