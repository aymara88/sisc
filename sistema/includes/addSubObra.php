<?php

require_once ('../conexion.php');

if(isset($_POST['id_proyecto_subobras']))
{
	$subobras_nuevo = $nueva_subobra = $subobras = $id = $duracion = $descripcion = $clonar_subobra = $subobra_nombre_nuevo = "";
	
	if(isset($_POST['subobras_nuevo']))
	$subobras_nuevo = (int)$_POST['subobras_nuevo'];
	if(isset($_POST['nueva_subobra']))
	$nueva_subobra = trim($_POST['nueva_subobra']);
	if(isset($_POST['subobras']))
	$subobras = (int)$_POST['subobras'];
	if(isset($_POST['id_proyecto_subobras']))
	$id = (int)$_POST['id_proyecto_subobras'];
	if(isset($_POST['subobra_duracion']))
	$duracion = (float)$_POST['subobra_duracion'];
	if(isset($_POST['descripcion_subobra']))
	$descripcion = $_POST['descripcion_subobra'];
	if(isset($_POST['clonar_subobra_checkbox']))
	$clonar_subobra = $_POST['clonar_subobra_checkbox'];
	if(isset($_POST['clonar_subobra_nombre']))
	$subobra_nombre_nuevo = $_POST['clonar_subobra_nombre'];
	
	if($subobras_nuevo > 2)
	{
		$alert = "El valor de la subobra es incorrecto";
		$code = 1;
	}
	else if($subobras_nuevo == 1 && empty($nueva_subobra))
	{
		$alert = "No se puede crear una subobra sin un nombre";
		$code = 2;
	}
	else if($subobras_nuevo == 2 && $subobras == 0)
	{
		$alert = "Subobra existente incorrecta";
		$code = 3;
	}
	else if($duracion == 0)
	{
		$alert = "La subobra debe tener una duración válida";
		$code = 10;
	}
	else if($id == 0)
	{
		$alert = "Proyecto incorrecto, debes elegir un proyecto existente para agregar subobras";
		$code = 11;
	}
	else if(empty($descripcion))
	{
		$alert = "Debe agregar una descripción para la subobra actual.";
		$code = 12;		
	}
	else
	{
		if($subobras_nuevo == 1)
		{
			$nombre = trim($nueva_subobra);
		}
		else if($subobras_nuevo == 2)
		{
			$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$subobras' AND estatus=1 LIMIT 1");
			$result = mysqli_fetch_array($query);				
			$nombre = trim($result['nombre_sub_obra']);
		}
		if(empty($nombre))
		{
			$alert = "No se puede crear una subobra sin un nombre correcto";
			$code = 4;
		}
		$nombre = mysqli_real_escape_string($conection, $nombre);
		$id = mysqli_real_escape_string($conection, $id);
		$descripcion = mysqli_real_escape_string($conection, $descripcion);
		$duracion = mysqli_real_escape_string($conection, $duracion);
		$query = mysqli_query($conection, "SELECT * FROM subobras WHERE nombre_sub_obra='$nombre' AND estatus=1 LIMIT 1");
		$result = mysqli_fetch_array($query);
		if ($result > 0) {
			$compare = mysqli_query($conection, "SELECT * FROM subobras WHERE nombre_sub_obra='$nombre' AND estatus=1 AND id_proyecto='$id'");
			$rescomp = mysqli_fetch_array($compare);
			if ($rescomp > 0) {
				if($rescomp['estatus'] == 0)
				{
					if($clonar_subobra == 1 && !empty($subobra_nombre_nuevo))
					{
						ClonarSubObra($subobras, $id, $subobra_nombre_nuevo);
						$alert = "Subobra asignada correctamente!";											
					}
					else
					{
						$alert = "No se puede clonar una subobra con el nombre asignado, porque ya existe.";
					}					
					//mysqli_query($conection, "UPDATE subobras SET estatus=1 WHERE nombre_sub_obra='$nombre' AND id_proyecto='$id'");
				}
				else
				{
					ClonarSubObraProyecto($subobras, $id, $subobra_nombre_nuevo, $duracion);
					$alert = "La subobra se ha clonado correctamente para el proyecto actual.";
				}
				$code = 5;
			}
			else if($rescomp == 0 && $result > 0){
				ClonarSubObra($subobras, $id, $nombre);
				$query_pn = mysqli_query($conection, "SELECT nombre_proyecto FROM obras WHERE id_proyecto='$id' AND estatus=1");
				$res_pn = mysqli_fetch_array($query_pn);
				$nombre_proyecto = $res_pn['nombre_proyecto'];
				$alert = "La subobra actual y todos sus elementos se han clonado para el proyecto $nombre_proyecto";
				$code = 5;
			}
			else{
				$alert = "La subobra que deseas crear ya existe!";
				$code = 5;
			}
		}else{
			$query_insert = mysqli_query($conection,"INSERT INTO subobras(nombre_sub_obra, descripcion_subobra, id_proyecto, duracion, costo_estimado, estatus) VALUES('$nombre','$descripcion', '$id', '$duracion', 0, 1)");
			if ($query_insert){
				$update_query = mysqli_query($conection,"UPDATE obras SET duracion_proyecto=duracion_proyecto+'$duracion' WHERE id_proyecto='$id'");
		$query_sub_obra = mysqli_query($conection,"SELECT * FROM subobras												
												WHERE estatus=1 AND id_proyecto='$id'
												ORDER BY id_sub_obra
								");
		$resultado_sub_obra = mysqli_num_rows($query_sub_obra);						
		$template = "<table border=\"0\" width=\"100%\" id=\"subobras\">
		<tr>
			<td>ID</td>
			<td>Nombre</td>
			<td>Descripción</td>
			<td>Duración</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";				
		$id_conteo = 0;
		while($row = mysqli_fetch_array($query_sub_obra))
		{
			$id_conteo++;
			$template .= "
			<tr id=\"tres{$row['id_sub_obra']}\">
				<td>" . (int)$id_conteo . "</td>
				<td>" . htmlspecialchars($row['nombre_sub_obra']) . "</td>
				<td>" . htmlspecialchars($row['descripcion_subobra']) . "</td>
				<td>" . (float)$row['duracion'] . "</td>
				<td>" . "$".number_format($row['costo_estimado'],2,".",",") . "</td>
				<td><a href=\"EliminarSubObra.php?id=" . $row['id_sub_obra'] . "\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
			</tr>
			";
		}
		$template .= "</table>";

				$alert="Subobra creada correctamente!<br>$template";
				$code = 6;
			}else{
				$alert = "Error al crear subobra!";
				$code = 7;
			}
		}
	}
}
else
{
	$alert = "Necesitas seleccionar un proyecto válido";
	$code = 8;
}

echo $alert;

function ClonarSubObra($id, $id_proyecto, $nombre){
	global $conection;
	$id = (int)$id;
	$id_proyecto = (int)$id_proyecto;
	if($id == 0 || $id_proyecto == 0)
		return false;
	$query_select = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id' AND estatus=1 LIMIT 1");
	$subobra = mysqli_fetch_array($query_select);
	$nombre = mysqli_real_escape_string($conection, $nombre);
	$descripcion = $_POST['descripcion_subobra'];
	$duracion = (float)$subobra['duracion'];
	$costo = (float)$subobra['costo_estimado'];
	$query_insert = mysqli_query($conection,"INSERT INTO subobras(nombre_sub_obra, descripcion_subobra, id_proyecto, duracion, costo_estimado, estatus) VALUES('$nombre','$descripcion', '$id_proyecto', '$duracion', $costo, 1)");

	// Cargar los datos para asignarle la subobra correcta.
	$query_subobra_id = mysqli_query($conection, "SELECT * FROM subobras WHERE nombre_sub_obra='$nombre' AND id_proyecto='$id_proyecto' AND estatus=1 LIMIT 1");
	$subobra = mysqli_fetch_array($query_subobra_id);
	$id_sub_obra = (int)$subobra['id_sub_obra'];

	$query_selecth = mysqli_query($conection, "SELECT * FROM estimacion_herramienta WHERE estatus=1 AND id_sub_obra='$id'");
	while($herramienta = mysqli_fetch_array($query_selecth))
	{
		$codigo = mysqli_real_escape_string($conection, $herramienta['codigo_herramientas']);
		$cantidad = (float)$herramienta['tiempo_uso'];
		$precio = (float)$herramienta['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_herramienta(codigo_herramientas,tiempo_uso,costo,id_sub_obra,estatus) VALUES('$codigo','$cantidad', '$precio', '$id_sub_obra',1)");
	}
	$query_selectm1 = mysqli_query($conection, "SELECT * FROM estimacion_mano_obra WHERE estatus=1 AND id_sub_obra='$id'");
	while($mano_obra = mysqli_fetch_array($query_selectm1))
	{
		$codigo_mano_obra = mysqli_real_escape_string($conection, $mano_obra['codigo_mano_obra']);
		$cantidad = (float)$mano_obra['tiempo_uso'];
		$precio = (float)$mano_obra['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_mano_obra(codigo_mano_obra,tiempo_uso,costo,id_sub_obra) VALUES('$codigo_mano_obra','$cantidad', '$precio', '$id_sub_obra')");		
	}	
	$query_selectm2 = mysqli_query($conection, "SELECT * FROM estimacion_maquinaria WHERE estatus=1 AND id_sub_obra='$id'");
	while($maquinaria = mysqli_fetch_array($query_selectm2))
	{
		$id_maquinaria = mysqli_real_escape_string($conection, $maquinaria['codigo_maquinaria']);
		$duracion = (float)$maquinaria['tiempo_uso'];
		$precio = (float)$maquinaria['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_maquinaria(codigo_maquinaria,tiempo_uso,costo,id_sub_obra) VALUES('$id_maquinaria','$duracion', '$precio', '$id_sub_obra')");
	}	
	$query_selectm3 = mysqli_query($conection, "SELECT * FROM estimacion_material WHERE estatus=1 AND id_sub_obra='$id'");	
	while($material = mysqli_fetch_array($query_selectm3))
	{
		$codigo_material = mysqli_real_escape_string($conection, $material['codigo_material']);
		$cantidad = (float)$material['cantidad_usar'];
		$precio = (float)$material['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_material(codigo_material,cantidad_usar,costo,id_sub_obra) VALUES('$codigo_material','$cantidad', '$precio', '$id_sub_obra')");
	}	
}

function ClonarSubObraProyecto($id, $id_proyecto, $nombre, $duracion){
	global $conection;
	$id = (int)$id;
	$id_proyecto = (int)$id_proyecto;
	if($id == 0 || $id_proyecto == 0)
		return false;
	$query_select = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id' AND estatus=1 LIMIT 1");
	$subobra = mysqli_fetch_array($query_select);
	$nombre = mysqli_real_escape_string($conection, $nombre);
	$descripcion = $_POST['descripcion_subobra'];
	$duracion = mysqli_real_escape_string($conection, $duracion);
	$costo = (float)$subobra['costo_estimado'];
	$query_insert = mysqli_query($conection,"INSERT INTO subobras(nombre_sub_obra, descripcion_subobra, id_proyecto, duracion, costo_estimado, estatus) VALUES('$nombre','$descripcion', '$id_proyecto', '$duracion', $costo, 1)");

	// Cargar los datos para asignarle la subobra correcta.
	$query_subobra_id = mysqli_query($conection, "SELECT * FROM subobras WHERE nombre_sub_obra='$nombre' AND id_proyecto='$id_proyecto' AND estatus=1 LIMIT 1");
	$subobra = mysqli_fetch_array($query_subobra_id);
	$id_sub_obra = (int)$subobra['id_sub_obra'];
	
	$query_selecth = mysqli_query($conection, "SELECT * FROM estimacion_herramienta WHERE estatus=1 AND id_sub_obra='$id'");
	while($herramienta = mysqli_fetch_array($query_selecth))
	{
		$codigo = mysqli_real_escape_string($conection, $herramienta['codigo_herramientas']);
		$cantidad = (float)$herramienta['tiempo_uso'];
		$precio = (float)$herramienta['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_herramienta(codigo_herramientas,tiempo_uso,costo,id_sub_obra,estatus) VALUES('$codigo','$cantidad', '$precio', '$id_sub_obra',1)");
	}
	$query_selectm1 = mysqli_query($conection, "SELECT * FROM estimacion_mano_obra WHERE estatus=1 AND id_sub_obra='$id'");
	while($mano_obra = mysqli_fetch_array($query_selectm1))
	{
		$codigo_mano_obra = mysqli_real_escape_string($conection, $mano_obra['codigo_mano_obra']);
		$cantidad = (float)$mano_obra['tiempo_uso'];
		$precio = (float)$mano_obra['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_mano_obra(codigo_mano_obra,tiempo_uso,costo,id_sub_obra) VALUES('$codigo_mano_obra','$cantidad', '$precio', '$id_sub_obra')");		
	}	
	$query_selectm2 = mysqli_query($conection, "SELECT * FROM estimacion_maquinaria WHERE estatus=1 AND id_sub_obra='$id'");
	while($maquinaria = mysqli_fetch_array($query_selectm2))
	{
		$id_maquinaria = mysqli_real_escape_string($conection, $maquinaria['codigo_maquinaria']);
		$duracion = (float)$maquinaria['tiempo_uso'];
		$precio = (float)$maquinaria['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_maquinaria(codigo_maquinaria,tiempo_uso,costo,id_sub_obra) VALUES('$id_maquinaria','$duracion', '$precio', '$id_sub_obra')");
	}	
	$query_selectm3 = mysqli_query($conection, "SELECT * FROM estimacion_material WHERE estatus=1 AND id_sub_obra='$id'");	
	while($material = mysqli_fetch_array($query_selectm3))
	{
		$codigo_material = mysqli_real_escape_string($conection, $material['codigo_material']);
		$cantidad = (float)$material['cantidad_usar'];
		$precio = (float)$material['costo'];
		$query_insert = mysqli_query($conection,"INSERT INTO estimacion_material(codigo_material,cantidad_usar,costo,id_sub_obra) VALUES('$codigo_material','$cantidad', '$precio', '$id_sub_obra')");
	}	
}
?>