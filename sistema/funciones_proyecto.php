<?php
function get_post_data(){
	include "conexion.php";	
	global $alert;
	global $code; // to made work code variable
    global $nombre_proyecto;
    if(isset($_POST['btn-signup'])){
		if($_GET['action'] == 'nuevo_proyecto'){
			$alert='';
			$nombre = $descripcion = $cliente = "";
			$costo = $duracion = 0;
			if(isset($_POST['nombre']))
				$nombre = strtoupper($_POST['nombre']);
			if(isset($_POST['descripcion']))
				$descripcion = strtoupper($_POST['descripcion']);
			if(isset($_POST['costo']))
				$costo = (float)$_POST['costo'];
			if(isset($_POST['duracion']))
				$duracion = (int)$_POST['duracion'];
			if(isset($_POST['cliente']))
				$cliente = (int)$_POST['cliente'];
			if(empty($nombre)){
				$alert = "Introduce el nombre del proyecto!";
				$code = 2;
			}else if(empty($descripcion)){
				$alert = "Debe introducir una descripción para el proyecto.";
				$code = 3;
			}else if(empty($cliente)){
				$alert = "Selecciona un cliente para el proyecto!";
				$code = 6;
			}else if(!is_numeric($cliente)){
				$alert = "ID de cliente incorrecto!";
				$code = 6;
			}			
			else{
				$query = mysqli_query($conection, "SELECT * FROM obras WHERE nombre_proyecto='$nombre'");
				$result = mysqli_fetch_array($query);
				if ($result > 0) {
					$alert = "El proyecto que deseas crear ya existe!";
					$code = 7;
				}else{
					$query_insert = mysqli_query($conection,"INSERT INTO obras(nombre_proyecto, descripcion_proyecto, costo_estimado_proyecto, duracion_proyecto, id_cliente) VALUES('$nombre','$descripcion', '$costo', '$duracion', '$cliente')");

					if ($query_insert){
						 
						$alert="Proyecto creado correctamente!";
						$code = 8;
						$nombre_proyecto = $nombre;
					}else{
						$alert = "Error al crear proyecto!";
						$code = 9;
					}
				}
			}						
		}
	}
	else if(isset($_POST['sndBtnSubObras'])){
		if($_GET['action'] == 'crear_subobra' && isset($_POST['id_proyecto_subobras']))
		{
			$subobras_nuevo = (int)$_POST['subobras_nuevo'];
			$nueva_subobra = trim($_POST['nueva_subobra']);
			$subobras = (int)$_POST['subobras'];
			$id = (int)$_POST['id_proyecto_subobras'];
			$duracion = (int)$_POST['subobra_duracion'];
			$descripcion = "SIN DESCRIPCION";
			
			if($subobras_nuevo < 1 || $subobras > 2)
			{
				$alert = "El valor de la subobras es incorrecto";
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
			if($duracion == 0)
			{
				$alert = "La subobra debe tener una duración válida";
				$code = 10;
			}
			if($id == 0)
			{
				$alert = "Proyecto incorrecto, debes elegir un proyecto existente para agregar subobras";
				$code = 11;
			}		
			else
			{
				if($subobras_nuevo == 1)
				{
					$nombre = trim($nueva_subobra);
				}
				else if($subobras_nuevo == 2)
				{
					$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$subobras' LIMIT 1");
					$result = mysqli_fetch_array($query);				
					$nombre = trim($result['nombre_sub_obra']);
				}
				if(empty($nombre))
				{
					$alert = "No se puede crear una subobra sin un nombre correcto";
					$code = 4;
				}
				$nombre = mysqli_real_escape_string($conection, $nombre);
				$query = mysqli_query($conection, "SELECT * FROM subobras WHERE nombre_sub_obra='$nombre'");
				$result = mysqli_fetch_array($query);
				if ($result > 0) {
					$alert = "La subobra que deseas crear ya existe!";
					$code = 5;
				}else{
					$query_insert = mysqli_query($conection,"INSERT INTO subobras(nombre_sub_obra, descripcion_subobra, id_proyecto, duracion, costo_estimado) VALUES('$nombre','$descripcion', '$id', '$duracion', 0)");
					if ($query_insert){
						$alert="Subobra creada correctamente!";
						$code = 6;
						header("location: crear_proyecto.php");
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
	}

	else if(isset($_POST['sndBtnMaquinaria'])){
		if($_GET['action'] == 'estimacion_maquinaria'){
			$id_proyecto = (int)$_POST['id_proyecto_maquinaria'];
			$id_sub_obra = (int)$_POST['id_sub_proyecto_maquinaria'];
			$id_maquinaria = $id = $precio = $duracion = $precio_total = "";
			if(isset($_POST['maquinaria_select']))
				$id_maquinaria = $_POST['maquinaria_select'];
			if(isset($_POST['maquinaria_precio']))
				$precio = (float)$_POST['maquinaria_precio'];
			if(isset($_POST['maquinaria_duracion']))
				$duracion = (int)$_POST['maquinaria_duracion'];
			if(isset($_POST['maquinaria_precio_total']))
				$precio_total = (float)$_POST['maquinaria_precio_total'];
			
			if(empty($id_maquinaria))
			{
				$alert = "La maquinaria elegida es incorrecta";
				$code = 1;
			}
			else if($id_proyecto == 0)
			{
				$alert = "Debe elegir un proyecto antes de continuar";
				$code = 2;
			}
			else if($id_sub_obra == 0)
			{
				$alert = "Debe elegir una subobra correcta antes de continuar";
				$code = 3;		
			}
			else if(empty($precio) || $precio <= 0 || !is_numeric($precio))
			{
				$alert = "El precio de la maquiria debe ser agregado y debe ser un valor mayor a 0";
				$code = 4;
			}
			else if(empty($duracion) || $duracion <= 0 || !is_numeric($duracion))
			{
				$alert = "Debe agregar una duración para el proyecto";
				$code = 5;
			}
			else if(empty($precio_total) || $precio_total <= 0 || !is_numeric($precio_total))
			{
				$alert = "El precio total de la maquiria debe ser agregado y debe ser un valor mayor a 0";
				$code = 6;		
			}
			else
			{
				$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id_sub_obra' AND id_proyecto='$id_proyecto'");
				$result = mysqli_fetch_array($query);
				if ($result > 0) {
					$query_insert = mysqli_query($conection,"INSERT INTO estimacion_maquinaria(codigo_maquinaria,tiempo_uso,costo,id_sub_obra) VALUES('$id_maquinaria','$duracion', '$precio', '$id_sub_obra')");
					if ($query_insert){
						$update_query = mysqli_query($conection,"UPDATE subobras SET costo_estimado=costo_estimado+$precio_total WHERE id_proyecto='$id_proyecto' AND id_sub_obra='$id_sub_obra'");
						$update_query = mysqli_query($conection,"UPDATE obras SET costo_estimado_proyecto=costo_estimado_proyecto+$precio_total WHERE id_proyecto='$id_proyecto'");
			
						$querym = mysqli_query($conection, "SELECT em.*, m.descripcion_maquinaria FROM estimacion_maquinaria em
															LEFT JOIN maquinaria m
															ON em.codigo_maquinaria=m.codigo_maquinaria
															WHERE id_sub_obra='$id_sub_obra'
														");										
						$template = "<table border=\"0\" width=\"100%\" id=\"maquinaria\">
				<tr>
					<td>ID</td>
					<td>Descripción</td>
					<td>Tiempo de Uso</td>
					<td>Costo</td>
					<td>Acciones</td>
				</tr>";
						while($resultm = mysqli_fetch_array($querym)){
							$template .= "
					<tr>
						<td>" . $resultm['id_estimacion_maquinaria'] . "</td>
						<td>" . $resultm['descripcion_maquinaria'] . "</td>
						<td>" . $resultm['tiempo_uso'] . "</td>
						<td>" . $resultm['costo'] . "</td>
						<td><a href=\"EliminarMaquinaria.php?id=" . $resultm['id_estimacion_maquinaria'] . "\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>				
					</tr>
					";
						}
						$template .= "</table>";
						
						$alert="Se agrego la maquinaria a la subobra correctamente!<br>$template";
						$code = 7;
					}else{
						$alert = "Error al intentar agregar la maquinaria a la subobra!";
						$code = 8;
					}
				}
				else{
					$alert = "No se pueden agregar elementos a una subobra que no existe!";
					$code = 9;			
				}
			}		
		}
	}
	
	else if(isset($_POST['sndBtnMaterial'])){
		if($_GET['action'] == 'estimacion_materiales'){
				$id_proyecto = (int)$_POST['id_proyecto_material'];
				$id_sub_obra = (int)$_POST['id_sub_proyecto_material'];
				$id_familia = $id_unidad = $codigo_material = $cantidad = "";
				if(isset($_POST['material_select1']))
					$id_familia = $_POST['material_select1'];
				if(isset($_POST['material_select3']))
					$id_unidad = $_POST['material_select3'];
				if(isset($_POST['material_select2']))
					$codigo_material = $_POST['material_select2'];
				if(isset($_POST['material_precio']))
					$precio = (float)$_POST['material_precio'];
				if(isset($_POST['material_cantidad']))
					$cantidad = (int)$_POST['material_cantidad'];
				if($id_proyecto == 0)
				{
					$alert = "Debe elegir un proyecto antes de continuar";
					$code = 1;
				}
				else if($id_sub_obra == 0)
				{
					$alert = "Debe elegir una subobra correcta antes de continuar";
					$code = 2;
				}
				else if(empty($id_familia) || $id_familia <= 0 || !is_numeric($id_familia))
				{
					$alert = "Debe seleccionar una familia correcta";
					$code = 3;
				}	
				else if(empty($id_unidad) || $id_unidad <= 0 || !is_numeric($id_unidad))
				{
					$alert = "Debe seleccionar una unidad correcta";
					$code = 4;
				}
				else if(empty($codigo_material))
				{
					$alert = "Debe seleccionar un material correcto";
					$code = 5;
				}	
				else if(empty($precio) || $precio <= 0 || !is_numeric($precio))
				{
					$alert = "El precio del material asignado no es correcto";
					$code = 6;
				}
				else if(empty($cantidad) || $cantidad <= 0 || !is_numeric($cantidad))
				{
					$alert = "El precio del material asignado no es correcto";
					$code = 6;
				}	
				else
				{
					$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id_sub_obra' AND id_proyecto='$id_proyecto'");
					$result = mysqli_fetch_array($query);
					if ($result > 0) {
						$query_insert = mysqli_query($conection,"INSERT INTO estimacion_material(codigo_material,cantidad_usar,costo,id_sub_obra) VALUES('$codigo_material','$cantidad', '$precio', '$id_sub_obra')");
						if ($query_insert){
							$precio_total = $precio * $cantidad;
							$update_query = mysqli_query($conection,"UPDATE subobras SET costo_estimado=costo_estimado+$precio_total WHERE id_proyecto='$id_proyecto' AND id_sub_obra='$id_sub_obra'");
							$update_query = mysqli_query($conection,"UPDATE obras SET costo_estimado_proyecto=costo_estimado_proyecto+$precio_total WHERE id_proyecto='$id_proyecto'");
				
							$querym = mysqli_query($conection, "SELECT em.*, m.descripcion_material FROM estimacion_material em
																LEFT JOIN materiales m
																ON em.codigo_material=m.codigo_material
																WHERE id_sub_obra='$id_sub_obra'
															");										
							$template = "<table border=\"0\" width=\"100%\" id=\"materiales\">
					<tr>
						<td>ID</td>
						<td>Descripción</td>
						<td>Duración</td>
						<td>Costo</td>
						<td>Acciones</td>
					</tr>";
							while($resultm = mysqli_fetch_array($querym)){
								$template .= "
						<tr>
							<td>" . $resultm['id_estimacion_material'] . "</td>
							<td>" . $resultm['descripcion_material'] . "</td>
							<td>" . $resultm['cantidad_usar'] . "</td>
							<td>" . $resultm['costo'] . "</td>
							<td><a href=\"EliminarMaterial.php?id=" . $resultm['id_estimacion_material'] . "\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
						</tr>
						";
							}
							$template .= "</table>";
							
							$alert="Material agregado a la subobra correctamente!<br>$template";
							$code = 7;
						}else{
							$alert = "Error al intentar agregar material a la subobra!";
							$code = 8;
						}
					}
					else{
						$alert = "No se pueden agregar elementos a una subobra que no existe!";
						$code = 9;			
					}
				}
		}
	}
	
	else if(isset($_POST['sndBtnManoObra'])){
		if($_GET['action'] == 'estimacion_mano_obra'){
				$id_proyecto = (int)$_POST['id_proyecto_manobra'];
				$id_sub_obra = (int)$_POST['id_sub_proyecto_manobra'];
				$precio = $cantidad = $codigo_mano_obra = "";
				if(isset($_POST['mano_obra_dia']))
					$precio = (float)$_POST['mano_obra_dia'];
				if(isset($_POST['mano_obra_cantidad']))
					$cantidad = (int)$_POST['mano_obra_cantidad'];
				if(isset($_POST['mano_obra_select']))
					$codigo_mano_obra = $_POST['mano_obra_select'];
				if($id_proyecto == 0)
				{
					$alert = "Debe elegir un proyecto antes de continuar";
					$code = 1;
				}
				else if($id_sub_obra == 0)
				{
					$alert = "Debe elegir una subobra correcta antes de continuar";
					$code = 2;
				}
				else if(empty($precio) || $precio <= 0 || !is_numeric($precio))
				{
					$alert = "Debe seleccionar un precio correcto";
					$code = 3;
				}	
				else if(empty($cantidad) || $cantidad <= 0 || !is_numeric($cantidad))
				{
					$alert = "Debe seleccionar una cantidad correcta";
					$code = 4;
				}
				else if(empty($codigo_mano_obra))
				{
					$alert = "Debe seleccionar una mano de obra";
					$code = 5;
				}	
				else
				{
					$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id_sub_obra' AND id_proyecto='$id_proyecto'");
					$result = mysqli_fetch_array($query);
					if ($result > 0) {
						$query_insert = mysqli_query($conection,"INSERT INTO estimacion_mano_obra(codigo_mano_obra,tiempo_uso,costo,id_sub_obra) VALUES('$codigo_mano_obra','$cantidad', '$precio', '$id_sub_obra')");
						if ($query_insert){
							$total = "";
							$precio_total = $precio * $cantidad;
							$update_query = mysqli_query($conection,"UPDATE subobras SET costo_estimado=costo_estimado+$precio_total WHERE id_proyecto='$id_proyecto' AND id_sub_obra='$id_sub_obra'");
							$update_query = mysqli_query($conection,"UPDATE obras SET costo_estimado_proyecto=costo_estimado_proyecto+$precio_total WHERE id_proyecto='$id_proyecto'");
				
							$querym = mysqli_query($conection, "SELECT em.*, m.descripcion_mano_obra FROM estimacion_mano_obra em
																LEFT JOIN mano_obra m
																ON em.codigo_mano_obra=m.codigo_mano_obra
																WHERE id_sub_obra='$id_sub_obra'
															");										
							$template = "<table border=\"0\" width=\"100%\" id=\"mano_obras\">
					<tr>
						<td>ID</td>
						<td>Descripción</td>
						<td>Duración</td>
						<td>Costo</td>
						<td>Acciones</td>
					</tr>";
							while($resultm = mysqli_fetch_array($querym)){
								$template .= "
						<tr>
							<td>" . $resultm['id_estimacion_manobra'] . "</td>
							<td>" . $resultm['descripcion_mano_obra'] . "</td>
							<td>" . $resultm['tiempo_uso'] . "</td>
							<td>" . $resultm['costo'] . "</td>
							<td><a href=\"EliminarManoObra.php?id=" . $resultm['id_estimacion_manobra'] . "\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
						</tr>
						";
							}
							$template .= "</table>";
							
							$alert="Mano de obra agregada a subobra!<br>$template";
							$code = 6;
						}else{
							$alert = "Error al intentar agregar mano de obra a la subobra!";
							$code = 7;
						}
					}
					else{
						$alert = "No se pueden agregar elementos a una subobra que no existe!";
						$code = 8;			
					}
				}
		}
	}
	
	else if(isset($_POST['sndBtnHerramienta'])){
		if($_GET['action'] == 'estimacion_herramienta'){
			$id_proyecto = (int)$_POST['id_proyecto_herramienta'];
			$id_sub_obra = (int)$_POST['id_sub_proyecto_herramienta'];
			$precio = $cantidad = $codigo = "";
			if(isset($_POST['herramienta_precio']))
				$precio = (float)$_POST['herramienta_precio'];
			if(isset($_POST['herramienta_cantidad']))
				$cantidad = (int)$_POST['herramienta_cantidad'];
			if(isset($_POST['herramienta_select']))
				$codigo = $_POST['herramienta_select'];
			if($id_proyecto == 0)
			{
				$alert = "Debe elegir un proyecto antes de continuar";
				$code = 1;
			}
			else if($id_sub_obra == 0)
			{
				$alert = "Debe elegir una subobra correcta antes de continuar";
				$code = 2;
			}
			else if(empty($precio) || $precio <= 0 || !is_numeric($precio))
			{
				$alert = "Debe seleccionar un precio correcto";
				$code = 3;
			}	
			else if(empty($cantidad) || $cantidad <= 0 || !is_numeric($cantidad))
			{
				$alert = "Debe seleccionar una cantidad correcta";
				$code = 4;
			}
			else if(empty($codigo))
			{
				$alert = "Debe seleccionar una herramienta correcta";
				$code = 5;
			}	
			else
			{
				$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id_sub_obra' AND id_proyecto='$id_proyecto'");
				$result = mysqli_fetch_array($query);
				if ($result > 0) {
					$query_insert = mysqli_query($conection,"INSERT INTO estimacion_herramienta(codigo_herramientas,tiempo_uso,costo,id_sub_obra) VALUES('$codigo','$cantidad', '$precio', '$id_sub_obra')");
					if ($query_insert){
						$precio_total = $precio * $cantidad;
						$update_query = mysqli_query($conection,"UPDATE subobras SET costo_estimado=costo_estimado+$precio_total WHERE id_proyecto='$id_proyecto' AND id_sub_obra='$id_sub_obra'");
						$update_query = mysqli_query($conection,"UPDATE obras SET costo_estimado_proyecto=costo_estimado_proyecto+$precio_total WHERE id_proyecto='$id_proyecto'");
			
						$template = "<table border=\"0\" width=\"100%\" id=\"herramientas\">
					<tr>
						<td>ID</td>
						<td>Descripción</td>
						<td>Duración</td>
						<td>Costo</td>
						<td>Acciones</td>
					</tr>";
						$querym = mysqli_query($conection, "SELECT eh.*, h.descripcion_herramienta FROM estimacion_herramienta eh
															LEFT JOIN herramientas h
															ON eh.codigo_herramientas=h.codigo_herramienta
															WHERE id_sub_obra='$id_sub_obra'
															ORDER BY id_estimacion_herramienta
														");										
						while($resultm = mysqli_fetch_array($querym)){
							$template .= "
					<tr>
						<td>" . (int)$resultm['id_estimacion_herramienta'] . "</td>
						<td>" . $resultm['descripcion_herramienta'] . "</td>
						<td>" . $resultm['tiempo_uso'] . "</td>
						<td>" . "$".number_format($resultm['costo'],2,".",",") . "</td>
						<td><a href=\"EliminarHerramienta.php?id=" . $resultm['id_estimacion_herramienta'] . "\" class=\"link_eliminar\"><i class=\"fas fa-trash\">&nbsp;</i> Eliminar</a></td>
					</tr>
					";
						}
						$template .= "</table>";
						
						$alert="Herramienta agregada a subobra!<br>$template";
						$code = 6;
					}else{
						$alert = "Error al intentar agregar herramienta a la subobra!";
						$code = 7;
					}
				}
				else{
					$alert = "No se pueden agregar elementos a una subobra que no existe!";
					$code = 8;			
				}
			}			
		}
	}	
}
?>