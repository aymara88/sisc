<?php
	
	require ('../conexion.php');
	
	$id_familia = (int)$_POST['id_familia'];
	$id_unidad = (int)$_POST['id_unidad'];
	$html = "";
	
	$query_materiales = mysqli_query($conection,"SELECT codigo_material, descripcion_material FROM materiales 
													WHERE id_familia='$id_familia' AND id_unidad='$id_unidad'
													ORDER BY descripcion_material
									");
	$resultado_materiales = mysqli_num_rows($query_materiales);
	if($resultado_materiales > 0){
		$html = "<option value=\"0\">Elegir material</option>";
		while($row = mysqli_fetch_array($query_materiales))
		{
			$html.= "<option value='".$row['codigo_material']."'>".$row['descripcion_material']."</option>";
		}
	}
	if(empty($html))
	{
		$html .= "<option value=\"0\">Elija Material y Unidad válidos...</option>";
	}
	echo $html;
?>		


