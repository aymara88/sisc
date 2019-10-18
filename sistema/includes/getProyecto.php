<?php
	require ('../conexion.php');
	$html = array();
	$id_proyecto = (int)$_GET['id'];
	$datos = 0;
	if($id_proyecto == 0)
		exit;
	$query_proyecto = mysqli_query($conection,"SELECT * FROM obras WHERE estatus=1 AND id_proyecto = '$id_proyecto' ORDER BY nombre_proyecto");
	$resultado_proyecto = mysqli_num_rows($query_proyecto);
	while($row = mysqli_fetch_array($query_proyecto))
	{
		$datos = 1;
		$html = array(
			"id" => (int)$row['id_proyecto'],
			"nombre" => htmlspecialchars($row['nombre_proyecto']),
			"costo" => (float)$row['costo_estimado_proyecto'],
			"duracion" => (float)$row['duracion_proyecto'],
			"descripcion" => htmlspecialchars($row['descripcion_proyecto']),
			"cliente" => (int)$row['id_cliente']
		);
	}
	if($datos == 0)
	{
		$html = array("nombre" => "No hay proyectos actualmente", "duracion" => "");
	}
	header("Content-type: application/json; charset=utf8");
	echo json_encode($html);
	exit;
?>		


