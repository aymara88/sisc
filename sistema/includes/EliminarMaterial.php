<?php
	require ('../conexion.php');
	$datos = 0;
	$id = (int)$_GET['id'];
	if($id == 0)
	{
		$html = "El material que se desea eliminar no es correcto";
	}
	else
	{
		$query = mysqli_query($conection,"SELECT * FROM estimacion_material WHERE estatus=1 AND id_estimacion_material='$id' LIMIT 1");
		$resultado  = mysqli_num_rows($query);
		if($resultado > 0){
			$update_query = mysqli_query($conection,"UPDATE estimacion_material SET estatus=0 WHERE id_estimacion_material='$id'");
			$html = "Material eliminado correctamente";		
		}
		else{
			$html = "El material seleccionado no se puede eliminar";
		}
	}
	echo $html;
?>		


