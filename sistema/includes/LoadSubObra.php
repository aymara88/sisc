<?php
	require ('../conexion.php');
	$html = $alert = array();
	$id_proyecto = $id_sub_obra ="";
	$id_proyecto = (int)$_GET['id'];
	$id_sub_obra = (int)$_GET['subobra'];

	if($id_proyecto == 0 || empty($id_proyecto))
	{
		$alert[] = "Debe elegir un proyecto correcto";
	}
	else if($id_sub_obra == 0 || empty($id_sub_obra))
	{
		$alert[] = "Debe elegir una subobra correcta";
	}
	else
	{
		$html['duracion'] = 0;
		$html['descripcion'] = "";
		/*** Lista de las subobras ***/
		$datos1 = 0;
		$query_sub_obra = mysqli_query($conection,"SELECT * FROM subobras												
												WHERE estatus=1 AND id_proyecto='$id_proyecto'
												ORDER BY id_sub_obra
								");
		$resultado_sub_obra = mysqli_num_rows($query_sub_obra);
		
		$html['subobra'] = "<table border=\"0\" width=\"100%\" id=\"subobras\">
		<tr>
			<td>ID</td>
			<td>Nombre</td>
			<td>Descripción</td>
			<td>Duración</td>
			<td>Costo</td>
			<td width=\"100\">Acciones</td>
		</tr>";
		$id_conteo = 0;
		while($row = mysqli_fetch_array($query_sub_obra))
		{
			$id_conteo++;
			$datos1 = 1;
			if($id_sub_obra == $row['id_sub_obra'])
			{
				$html['duracion'] = (float)$row['duracion'];
				$html['descripcion'] = $row['descripcion_subobra'];
			}
			$html['subobra'] .= "
			<tr id=\"tres{$row['id_sub_obra']}\">
				<td>" . (int)$id_conteo . "</td>
				<td>" . htmlspecialchars($row['nombre_sub_obra']) . "</td>
				<td>" . htmlspecialchars($row['descripcion_subobra']) . "</td>
				<td>" . (float)$row['duracion'] . "</td>
				<td>" . "$".number_format($row['costo_estimado'],2,".",",") . "</td>
				<td><a href=\"#es{$row['id_sub_obra']}\" onclick=\"javascript:EliminarSubobra('{$row['id_sub_obra']}');return false;\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
			</tr>
			";
		}
		if($datos1 == 0)
		{
			$html['subobra'] .= "<tr>
			<td>No hay subobras en éste proyecto, cree una y comience a agregar los datos para el proyecto actual</td>
		</tr>";
		}
		$html['subobra'] .= "</table>";
		
		/*** Lista de Herramientas ***/
		$datos2 = 0;
		$query_herramientas = mysqli_query($conection,"SELECT eh.*, h.descripcion_herramienta FROM estimacion_herramienta eh
												LEFT JOIN herramientas h
												ON eh.codigo_herramientas=h.codigo_herramienta
												WHERE eh.estatus=1 AND id_sub_obra = '$id_sub_obra' 
												ORDER BY id_estimacion_herramienta
								");
		$resultado_herramientas = mysqli_num_rows($query_herramientas);

		$html['herramienta'] = "<table border=\"0\" width=\"100%\" id=\"herramientas\">
		<tr>
			<td>ID</td>
			<td>Nombre</td>
			<td>Cantidad</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
		$id_conteo2 = 0;
		while($rowh = mysqli_fetch_array($query_herramientas))
		{
			$id_conteo2++;
			$datos2 = 1;
			$html['herramienta'] .= "
			<tr id=\"treh{$rowh['id_estimacion_herramienta']}\">
				<td>" . (int)$id_conteo2 . "</td>
				<td>" . $rowh['descripcion_herramienta'] . "</td>
				<td>" . $rowh['tiempo_uso'] . "</td>
				<td>" . "$".number_format($rowh['costo'] * $rowh['tiempo_uso'],2,".",",") . "</td>
				<td><a href=\"#eh{$rowh['id_estimacion_herramienta']}\" id=\"eh{$rowh['id_estimacion_herramienta']}\" onclick=\"javascript:EliminarHerramienta('{$rowh['id_estimacion_herramienta']}');return false;\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
			</tr>
			";
		}
		if($datos2 == 0)
		{
			$html['herramienta'] .= "<tr>
			<td>No hay herramientas agregadas a la subobra actual.</td>
		</tr>";
		}		
		$html['herramienta'] .= "</table>";
	
		/*** Lista de la mano de obra ***/
		$datos3 = 0;
		$query_mano_obra = mysqli_query($conection,"SELECT emo.*, mo.descripcion_mano_obra FROM estimacion_mano_obra emo
												LEFT JOIN mano_obra mo
												ON emo.codigo_mano_obra=mo.codigo_mano_obra
												WHERE emo.estatus=1 AND id_sub_obra = '$id_sub_obra'
												ORDER BY id_estimacion_manobra
								");
		$resultado_mano_obra = mysqli_num_rows($query_mano_obra);

		$html['mano_obra'] = "<table border=\"0\" width=\"100%\" id=\"mano_obras\">
		<tr>
			<td>ID</td>
			<td>Nombre</td>
			<td>Duración</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
		$id_conteo3 = 0;
		while($rowm = mysqli_fetch_array($query_mano_obra))
		{
			$id_conteo3++;
			$datos3 = 1;
			$html['mano_obra'] .= "
			<tr id=\"trmo{$rowm['id_estimacion_manobra']}\">
				<td>" . $id_conteo3 . "</td>
				<td>" . $rowm['descripcion_mano_obra'] . "</td>
				<td>" . $rowm['tiempo_uso'] . "</td>
				<td>" . $rowm['costo'] * $rowm['tiempo_uso'] . "</td>
				<td><a href=\"#emo{$rowm['id_estimacion_manobra']}\" onclick=\"EliminarManoObra('{$rowm['id_estimacion_manobra']}')\" id=\"emo{$rowm['id_estimacion_manobra']}\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
			</tr>
			";
		}
		if($datos3 == 0)
		{
			$html['mano_obra'] .= "<tr>
			<td>No hay mano de obra actualmente en esta subobra</td>
		</tr>";
		}		
		$html['mano_obra'] .= "</table>";
		
		/*** Lista de la maquinaria ***/
		$datos4 = 0;
		$query_maquinaria = mysqli_query($conection,"SELECT em.*, m.descripcion_maquinaria FROM estimacion_maquinaria em
												LEFT JOIN maquinaria m
												ON em.codigo_maquinaria=m.codigo_maquinaria
												WHERE em.estatus=1 AND id_sub_obra = '$id_sub_obra'
												ORDER BY id_estimacion_maquinaria
								");
		$resultado_maquinaria = mysqli_num_rows($query_maquinaria);

		$html['maquinaria'] = "<table border=\"0\" width=\"100%\" id=\"maquinaria\">
		<tr>
			<td>ID</td>
			<td>Descripción</td>
			<td>Tiempo de Uso</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
		$id_conteo4 = 0;
		while($rowmaq = mysqli_fetch_array($query_maquinaria))
		{
			$id_conteo4++;
			$datos4 = 1;
			$html['maquinaria'] .= "
			<tr id=\"emaq{$rowmaq['id_estimacion_maquinaria']}\">
				<td>" . $id_conteo4 . "</td>
				<td>" . $rowmaq['descripcion_maquinaria'] . "</td>
				<td>" . $rowmaq['tiempo_uso'] . " hrs.</td>
				<td>" . $rowmaq['costo'] * $rowmaq['tiempo_uso'] . "</td>
				<td><a href=\"#ema{$rowmaq['id_estimacion_maquinaria']}\" onclick=\"EliminarMaquinaria('{$rowmaq['id_estimacion_maquinaria']}')\" id=\"ema{$rowmaq['id_estimacion_maquinaria']}\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>				
			</tr>
			";
		}
		if($datos4 == 0)
		{
			$html['maquinaria'] .= "<tr>
			<td>No hay maquinaria agregada para esta subobra</td>
		</tr>";
		}		
		$html['maquinaria'] .= "</table>";
		
		/*** Lista de la materiales ***/
		$datos5 = 0;
		$query_material = mysqli_query($conection,"SELECT em.*, m.descripcion_material FROM estimacion_material em
												LEFT JOIN materiales m
												ON em.codigo_material=m.codigo_material
												WHERE em.estatus=1 AND id_sub_obra = '$id_sub_obra'
												ORDER BY id_estimacion_material
								");
		$resultado_material = mysqli_num_rows($query_material);

		$html['material'] = "<table border=\"0\" width=\"100%\" id=\"materiales\">
		<tr>
			<td>ID</td>
			<td>Descripción</td>
			<td>Cantidad</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
		$id_conteo5 = 0;
		while($rowmat = mysqli_fetch_array($query_material))
		{
			$id_conteo5++;
			$datos5 = 1;
			$html['material'] .= "
			<tr id=\"emat{$rowmat['id_estimacion_material']}\">
				<td>" . $id_conteo5 . "</td>
				<td>" . $rowmat['descripcion_material'] . "</td>
				<td>" . $rowmat['cantidad_usar'] . "</td>
				<td>" . $rowmat['costo'] * $rowmat['cantidad_usar'] . "</td>
				<td><a href=\"#mat{$rowmat['id_estimacion_material']}\" onclick=\"EliminarMaterial('{$rowmat['id_estimacion_material']}')\" id=\"#mat{$rowmat['id_estimacion_material']}\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
			</tr>
			";
		}
		if($datos5 == 0)
		{
			$html['material'] .= "<tr>
			<td>No hay materiales agregados a esta subobra</td>
		</tr>";
		}		
		$html['material'] .= "</table>";
	}
	
	header("Content-type: application/json; charset=utf8");
	echo json_encode($html);
	exit;
?>