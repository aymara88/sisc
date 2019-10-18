<?php
	
	require ('../conexion.php');
	$html = array();
	$id = mysqli_real_escape_string($conection, $_POST['id_elemento']);
	$query = mysqli_query($conection,"SELECT costo_mano_obra FROM mano_obra
									WHERE codigo_mano_obra='$id' LIMIT 1");
	$resultado = mysqli_num_rows($query);
	if($resultado > 0){
		while($row = mysqli_fetch_array($query))
		{
			$html = (float)$row['costo_mano_obra'];
		}
	}
	else{
		$html = 0;
	}
	echo $html;
?>		


