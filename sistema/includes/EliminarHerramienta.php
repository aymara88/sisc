<?php
	require ('../conexion.php');
	$datos = 0;
	$id = (int)$_GET['id'];
	if($id == 0)
	{
		$html = "La herramienta que se desea eliminar no es correcta";
	}
	else
	{
		$query = mysqli_query($conection,"SELECT * FROM estimacion_herramienta WHERE estatus=1 AND id_estimacion_herramienta='$id' LIMIT 1");
		$resultado  = mysqli_num_rows($query);
		if($resultado > 0){
			$update_query = mysqli_query($conection,"UPDATE estimacion_herramienta SET estatus=0 WHERE id_estimacion_herramienta='$id'");
			$html = "Herramienta eliminada correctamente";		
		}
		else{
			$html = "La herramienta seleccionada no se puede eliminar";
		}
	}
	echo $html;
?>		


