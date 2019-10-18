<?php
require_once "includes/verifica_sesion.php";

	include "conexion.php";
	$datos = 0;
	$id = (int)$_GET['id'];
	if($id == 0)
	{
		$html = "La mano de obra que se desea eliminar no es correcta";
	}
	else
	{
		$query = mysqli_query($conection,"SELECT * FROM estimacion_mano_obra WHERE estatus=1 AND id_estimacion_mano_obra='$id' LIMIT 1");
		$resultado  = mysqli_num_rows($query);
		if($resultado > 0){
			$update_query = mysqli_query($conection,"UPDATE estimacion_mano_obra SET estatus=0 WHERE id_estimacion_mano_obra='$id'");
			$html = "Mano de obra eliminada correctamente";		
		}
		else{
			$html = "La mano de obra seleccionada no se puede eliminar";
		}
	}
	echo $html;
	header("location: crear_proyecto.php");
?>		


