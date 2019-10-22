<?php

require_once ('../conexion.php');

if(isset($_GET['id']) && isset($_GET['subobra']))
{
	$alert = "";
	$id_proyecto = (int)$_GET['id'];
	$id_sub_obra = (int)$_GET['subobra'];
	$codigo = "";
	$precio = $cantidad = $precio_total = 0;
	if(isset($_POST['herramienta_precio']))
		$precio = (float)$_POST['herramienta_precio'];
	if(isset($_POST['herramienta_cantidad']))
		$cantidad = (float)$_POST['herramienta_cantidad'];
	if(isset($_POST['herramienta_select']))
		$codigo = $_POST['herramienta_select'];
	if(isset($_POST['herramienta_precio_total']))
		$precio_total = (float)$_POST['herramienta_precio_total'];
	
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
	else if(empty($precio_total) || $precio_total <= 0 || !is_numeric($precio_total))
	{
		$alert = "El precio total de la herramienta no es correcto";
		$code = 7;
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
													WHERE eh.estatus=1 AND id_sub_obra='$id_sub_obra'
													ORDER BY id_estimacion_herramienta
												");		
				$id_conteo = 0;	
                $rowh = 0;
				while($resultm = mysqli_fetch_array($querym)){
					$id_conteo++;
					$template .= "
			<tr id=\"treh{$rowh['id_estimacion_herramienta']}\">
				<td>" . (int)$id_conteo . "</td>
				<td>" . $resultm['descripcion_herramienta'] . "</td>
				<td>" . $resultm['tiempo_uso'] . "</td>
				<td>" . "$".number_format($resultm['costo'] * $resultm['tiempo_uso'],2,".",",") . "</td>
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
else
{
	$alert = "Necesitas seleccionar un proyecto válido y una subobra para agregar elementos";
	$code = 9;
}

echo $alert;
?>