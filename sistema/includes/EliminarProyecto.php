<?php
	require ('../conexion.php');
	$datos = 0;
	$id_proyecto = (int)$_GET['id'];
	if($id_proyecto == 0)
	{
		$html = "El proyecto que se desea eliminar no es correcto";
	}
	else
	{
		$query_proyecto = mysqli_query($conection,"SELECT * FROM obras WHERE id_proyecto = '$id_proyecto' LIMIT 1");
		$resultado_proyecto = mysqli_num_rows($query_proyecto);
		if($resultado_proyecto > 0){
			$obras_update = mysqli_query($conection,"UPDATE obras SET estatus=0 WHERE estatus=1 AND id_proyecto='$id_proyecto'");
			$subobras_update = mysqli_query($conection,"UPDATE subobras SET estatus=0 WHERE estatus=1 AND id_proyecto='$id_proyecto'");
			$html = "El proyecto seleccionado y todas sus subobras han sido eliminados correctamente";		
		}
		else{
			$html = "El proyecto seleccionado no se puede eliminar";
		}
	}
	echo $html;
?>		


