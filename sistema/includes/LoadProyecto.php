<?php
	require ('../conexion.php');
	$datos = 0;
	$id_proyecto = (int)$_GET['id'];
	$html = array();
	if($id_proyecto == 0)
		exit;
	$query_proyecto = mysqli_query($conection,"SELECT * FROM subobras 
												WHERE id_proyecto = '$id_proyecto'
												AND estatus=1
												ORDER BY id_sub_obra
								");
	$resultado_proyecto = mysqli_num_rows($query_proyecto);
	$html['subobras'] = "";
	$html['costo'] = 0;
	$html['duracion'] = 0;
	$html['resultado'] = "<table border=\"0\" width=\"100%\">
	<tr>
		<td>ID</td>
		<td>Nombre</td>
		<td>Duración</td>
		<td>Descripción</td>
		<td>Costo</td>
	</tr>";
	$id_conteo = 0;
	while($row = mysqli_fetch_array($query_proyecto))
	{
		$id_conteo++;
		$duracion = (float)$row['duracion'];
		$costo = (float)$row['costo_estimado'];
		if($row['duracion'] == 1)
			$mes = " mes";
		else
			$mes = " meses";
		$datos = 1;
		$html['duracion'] += $duracion;
		$html['costo'] += $costo;
		$html['subobras'] .= "<option value=\"{$row['id_sub_obra']}\">{$row['nombre_sub_obra']}</option>";
		$html['resultado'] .= "
		<tr>
			<td>" . (int)$id_conteo . "</td>
			<td>" .htmlspecialchars($row['nombre_sub_obra']) . "</td>
			<td>" .(float)$row['duracion'] . " ".$mes."</td>
			<td>" . htmlspecialchars($row['descripcion_subobra']) . "</td>
			<td>$" . number_format($row['costo_estimado'],2,".",",") . "</td>
		</tr>
		";
	}
	if($datos == 0)
	{
		$html['subobras'] .= "<option value=\"0\">Aún no hay subobras...</option>";		
		$html['resultado'] .= "
		<tr>
			<td colspan=\"5\">No hay subobras para este proyecto.</td>
		</tr>
		";
	}		
	$html['resultado'] .= "</table>";

	header("Content-type: application/json; charset=utf8");
	echo json_encode($html);
?>		


