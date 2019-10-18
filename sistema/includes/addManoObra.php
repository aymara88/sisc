<?php

require_once ('../conexion.php');

if(isset($_GET['id']) && isset($_GET['subobra']))
{
	$id_proyecto = (int)$_GET['id'];
	$id_sub_obra = (int)$_GET['subobra'];
	$precio = $cantidad = $codigo_mano_obra = "";
	if(isset($_POST['mano_obra_dia']))
		$precio = (float)$_POST['mano_obra_dia'];
	if(isset($_POST['mano_obra_cantidad']))
		$cantidad = (float)$_POST['mano_obra_cantidad'];
	if(isset($_POST['mano_obra_select']))
		$codigo_mano_obra = $_POST['mano_obra_select'];
	if(isset($_POST['mano_obra_precio_total']))
		$precio_total = (float)$_POST['mano_obra_precio_total'];
		
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
	else if(empty($precio_total) || $precio_total <= 0 || !is_numeric($precio_total))
	{
		$alert = "El precio total de la mano de obra asignado no es correcto";
		$code = 7;
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
													WHERE em.estatus=1 AND id_sub_obra='$id_sub_obra'
												");										
				$template = "<table border=\"0\" width=\"100%\" id=\"mano_obras\">
		<tr>
			<td>ID</td>
			<td>Código</td>
			<td>Duración</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
                $rowm = 0;
				$id_conteo = 0;
				while($resultm = mysqli_fetch_array($querym)){
					$id_conteo++;
					$template .= "
			<tr id=\"trmo{$rowm['id_estimacion_manobra']}\">
				<td>" . $id_conteo . "</td>
				<td>" . $resultm['descripcion_mano_obra'] . "</td>
				<td>" . $resultm['tiempo_uso'] . "</td>
				<td>" . $resultm['costo'] * $resultm['tiempo_uso'] . "</td>
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
else
{
	$alert = "Necesitas seleccionar un proyecto válido y una subobra para agregar elementos";
	$code = 9;
}

echo $alert;
?>