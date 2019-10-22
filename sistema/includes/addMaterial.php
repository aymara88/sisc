<?php

require_once ('../conexion.php');

if(isset($_GET['id']) && isset($_GET['subobra']))
{
	$id_proyecto = (int)$_GET['id'];
	$id_sub_obra = (int)$_GET['subobra'];
	$id_familia = $id_unidad = $codigo_material = $cantidad = $precio_total = "";
	if(isset($_POST['material_select1']))
		$id_familia = $_POST['material_select1'];
	if(isset($_POST['material_select3']))
		$id_unidad = $_POST['material_select3'];
	if(isset($_POST['material_select2']))
		$codigo_material = $_POST['material_select2'];
	if(isset($_POST['material_precio']))
		$precio = (float)$_POST['material_precio'];
	if(isset($_POST['material_cantidad']))
		$cantidad = (float)$_POST['material_cantidad'];
	if(isset($_POST['material_precio_total']))
		$precio_total = (float)$_POST['material_precio_total'];
	
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
		$code = 7;
	}
	else if(empty($precio_total) || $precio_total <= 0 || !is_numeric($precio_total))
	{
		$alert = "El precio total del material asignado no es correcto";
		$code = 7;
	}	
	else
	{
		$query = mysqli_query($conection, "SELECT * FROM subobras WHERE id_sub_obra='$id_sub_obra' AND estatus=1 AND id_proyecto='$id_proyecto'");
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
													WHERE em.estatus=1 AND id_sub_obra='$id_sub_obra'
												");										
				$template = "<table border=\"0\" width=\"100%\" id=\"materiales\">
		<tr>
			<td>ID</td>
			<td>Descripción</td>
			<td>Duración</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
				$id_conteo = 0;
                $rowmat = 0;
				while($resultm = mysqli_fetch_array($querym)){
					$id_conteo++;
					$template .= "
			<tr id=\"emat{$rowmat['id_estimacion_material']}\">
				<td>" . $id_conteo . "</td>
				<td>" . $resultm['descripcion_material'] . "</td>
				<td>" . $resultm['cantidad_usar'] . "</td>
				<td>" . $resultm['costo'] * $resultm['cantidad_usar'] . "</td>
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
else
{
	$alert = "Necesitas seleccionar un proyecto válido y una subobra para agregar elementos";
	$code = 10;
}

echo $alert;
?>