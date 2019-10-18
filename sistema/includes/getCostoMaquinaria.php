<?php
	
	require ('../conexion.php');
	$id = mysqli_real_escape_string($conection, $_POST['id_elemento']);
	$query = mysqli_query($conection,"SELECT costo_maquinaria FROM maquinaria WHERE codigo_maquinaria='$id' LIMIT 1");
	$resultado = mysqli_num_rows($query);
	if($resultado > 0){
		while($row = mysqli_fetch_array($query))
		{
			$html = (float)$row['costo_maquinaria'];
		}
	}
	else{
		$html = 0;
	}
	echo $html;
?>		


