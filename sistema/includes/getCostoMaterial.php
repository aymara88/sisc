<?php
	
	require ('../conexion.php');
	$html = array();
	$id = mysqli_real_escape_string($conection, $_POST['id_elemento']);
	$query = mysqli_query($conection,"SELECT m.codigo_material, m.costo_material, u.abreviatura_unidad FROM materiales m
										LEFT JOIN unidades u
										ON m.id_unidad=u.id_unidad
									WHERE m.codigo_material='$id' LIMIT 1") or die(mysqli_error);
	$resultado = mysqli_num_rows($query);
	if($resultado > 0){
		while($row = mysqli_fetch_array($query))
		{
			$html = array(
				"precio" => (float)$row['costo_material'],
				"unidad" => htmlspecialchars($row['abreviatura_unidad'])
			);
		}
	}
	else{
		$html = array("precio"=>0,"unidad"=>"");
	}
	header("Content-type: application/json; charset=utf8");	
	echo json_encode($html);
?>		


