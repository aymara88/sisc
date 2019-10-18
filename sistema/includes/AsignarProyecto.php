<?php
	require ('../conexion.php');
	$html = array();
	$id_proyecto = (int)$_POST['id_proyecto'];
	if($id_proyecto == 0)
		exit;
	$query_proyecto = mysqli_query($conection,"SELECT * FROM subobras WHERE estatus=1 AND id_proyecto='$id_proyecto' ORDER BY id_sub_obra");
	$resultado_proyecto = mysqli_num_rows($query_proyecto);
	if($resultado_proyecto > 0)
	{
		$subobras = $MontoTotal = $DuracionTotal = 0;
		while($row = mysqli_fetch_array($query_proyecto))
		{
			$datos = 1;
			$id_sub_obra = (int)$row['id_sub_obra'];
			$duracion = (float)$row['duracion'];
			$costo_obra = (float)GetCostos($row['id_sub_obra']);
			if($costo_obra > 0 || $duracion > 0)
			{
				$subobras++;
				mysqli_query($conection, "UPDATE subobras SET costo_estimado='$costo_obra', duracion='$duracion' WHERE id_proyecto='$id_proyecto' AND id_sub_obra='$id_sub_obra'");
				$MontoTotal = $MontoTotal + $costo_obra;
				$DuracionTotal = $DuracionTotal + $duracion;
			}
		}
		mysqli_query($conection, "UPDATE obras SET costo_estimado_proyecto='$MontoTotal', duracion_proyecto='$DuracionTotal', subobras='$subobras' WHERE id_proyecto='$id_proyecto'");		
		$html = array("message" => "Se ha actualizado el proyecto correctamente!", "costo" => number_format($MontoTotal,2,".",""), "duracion" => (float)$DuracionTotal);
	}
	else
	{
		$html = array("message" => "No hay proyectos actualmente", "costo" => 0, "duracion" => 0);
	}
	header("Content-type: application/json; charset=utf8");
	echo json_encode($html);
	exit;
	
	
function GetCostos($id_sub_obra){
	global $conection;
	$id_sub_obra = (int)$id_sub_obra;
	if($id_sub_obra == 0)
		return false;
	$query_costo_material = mysqli_query($conection,"SELECT SUM(costo*cantidad_usar) as TotalMaterial FROM estimacion_material
													WHERE estatus=1 AND id_sub_obra='$id_sub_obra' LIMIT 1");
	$resultado_costo_material = mysqli_fetch_array($query_costo_material);
	$resultado_costo_mat = (float)$resultado_costo_material['TotalMaterial'];
	
	$query_costo_maquinaria = mysqli_query($conection,"SELECT SUM(costo*tiempo_uso) as TotalMaquinaria FROM estimacion_maquinaria
													WHERE estatus=1 AND id_sub_obra='$id_sub_obra' LIMIT 1");
	$resultado_costo_maquinaria = mysqli_fetch_array($query_costo_maquinaria);
	$resultado_costo_maq = (float)$resultado_costo_maquinaria['TotalMaquinaria'];
	
	$query_costo_mano_obra = mysqli_query($conection,"SELECT SUM(costo*tiempo_uso) as TotalManoObra FROM estimacion_mano_obra
													WHERE estatus=1 AND id_sub_obra='$id_sub_obra' LIMIT 1");
	$resultado_costo_mano_obra = mysqli_fetch_array($query_costo_mano_obra);
	$resultado_costo_man = (float)$resultado_costo_mano_obra['TotalManoObra'];
	
	$query_costo_herramienta = mysqli_query($conection,"SELECT SUM(costo*tiempo_uso) as TotalHerramienta FROM estimacion_herramienta
													WHERE estatus=1 AND id_sub_obra='$id_sub_obra' LIMIT 1");
	$resultado_costo_herramienta = mysqli_fetch_array($query_costo_herramienta);
	$resultado_costo_her = (float)$resultado_costo_herramienta['TotalHerramienta'];
	
	$costo_proyecto = $resultado_costo_mat + $resultado_costo_maq + $resultado_costo_man + $resultado_costo_her;
	
	return $costo_proyecto;
}
?>