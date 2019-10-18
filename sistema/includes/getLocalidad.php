<?php
	require ('../conexion.php');
	
	$id_municipio = $_POST['id_municipio'];
	
	//$query = "SELECT id_localidad, nombre_localidad FROM localidades WHERE id_municipio = '$id_municipio' ORDER BY nombre_localidad";
	//$resultado=$mysqli->query($query);
	$query_localidad = mysqli_query($conection,"SELECT id_localidad, nombre_localidad FROM localidades WHERE id_municipio = '$id_municipio' ORDER BY nombre_localidad");
	$resultado_localidad = mysqli_num_rows($query_localidad);
    
    $html= "<option value=''>Seleccionar Localidad</option>";
	
	while($row = mysqli_fetch_array($query_localidad))
	{
		$html.= "<option value='".$row['id_localidad']."'>".$row['nombre_localidad']."</option>";
	}
	echo $html;
?>
