<?php
	
	require ('../conexion.php');
	
	$id_estado = $_POST['id_estado'];
	
	//$queryM = "SELECT id_municipio, nombre_municipio FROM municipios WHERE id_estado = '$id_estado' ORDER BY nombre_municipio";
	//$resultadoM = $mysqli->query($queryM);
	$query_municipios = mysqli_query($conection,"SELECT id_municipio, nombre_municipio FROM municipios WHERE id_estado = '$id_estado' ORDER BY nombre_municipio");
	$resultado_municipio = mysqli_num_rows($query_municipios);

	$html= "<option value=''>Seleccionar Municipio</option>";


	while($row = mysqli_fetch_array($query_municipios))
	{
		$html.= "<option value='".$row['id_municipio']."'>".$row['nombre_municipio']."</option>";
	}
	
	echo $html;
?>		


