<?php
	require ('../conexion.php');
	$datos = 0;
	$id = (int)$_GET['id'];
	if($id == 0)
	{
		$html = "La subobra que se desea eliminar no es correcta";
	}
	else
	{
		$query = mysqli_query($conection,"SELECT * FROM subobras WHERE id_sub_obra='$id' LIMIT 1");
		$resultado  = mysqli_num_rows($query);
		if($resultado > 0){
			$update_query = mysqli_query($conection,"UPDATE subobras SET estatus=0 WHERE id_sub_obra='$id'");
			$html = "Subobra eliminada correctamente";		
		}
		else{
			$html = "La subobra seleccionada no se puede eliminar";
		}
	}
	echo $html;
?>		


