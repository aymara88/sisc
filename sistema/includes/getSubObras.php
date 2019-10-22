<?php
	
	require ('../conexion.php');
	
	$id_proyecto = $_POST['id_proyecto'];

	$query = mysqli_query($conection,"SELECT id_sub_obra, nombre_sub_obra FROM subobras WHERE estatus=1 AND id_proyecto = '$id_proyecto' ORDER BY id_sub_obra DESC");
	$resultado = mysqli_num_rows($query);
	$html = "<option value=\"0\">Seleccionar una subobra</option>";
	while($row = mysqli_fetch_array($query))
	{
		$html.= "<option value='".$row['id_sub_obra']."'>".$row['nombre_sub_obra']."</option>";
	}
	
	echo $html;
?>		


