<?php
	
	require ('../conexion.php');
	
	$id_familia = (int)$_POST['id_familia'];
	$html = "";
	$query_un = mysqli_query($conection,"SELECT DISTINCT(m.id_unidad), u.descripcion FROM materiales m
										LEFT JOIN unidades u 
											ON m.id_unidad = u.id_unidad
										WHERE m.estatus=1 AND m.id_familia='$id_familia'
									ORDER BY abreviatura_unidad
						");
	$results_un = mysqli_num_rows($query_un);
	if($results_un > 0){
		$html = "<option value=\"0\">Seleccionar unidad de medida</option>";
		while($row = mysqli_fetch_array($query_un))
		{
			$html.= "<option value='".$row['id_unidad']."'>".$row['descripcion']."</option>";
		}
	}
	if(empty($html))
	{
		$html .= "<option value=\"0\">No hay unidades disponibles.</option>";
	}
	echo $html;
?>		


