<?php

require_once ('../conexion.php');

if(isset($_GET['id']) && isset($_GET['subobra']))
{
	$id_proyecto = (int)$_GET['id'];
	$id_sub_obra = (int)$_GET['subobra'];
	$id_maquinaria = $id = $precio = $duracion = $precio_total = "";
	if(isset($_POST['maquinaria_select']))
		$id_maquinaria = $_POST['maquinaria_select'];
	if(isset($_POST['maquinaria_precio']))
		$precio = (float)$_POST['maquinaria_precio'];
	if(isset($_POST['maquinaria_duracion']))
		$duracion = (float)$_POST['maquinaria_duracion'];
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
		$alert = "El precio total de la maquinaria no es correcto";
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
													WHERE em.estatus=1 AND id_sub_obra='$id_sub_obra'
												");										
				$template = "<table border=\"0\" width=\"100%\" id=\"maquinaria\">
		<tr>
			<td>ID</td>
			<td>Código</td>
			<td>Tiempo de Uso</td>
			<td>Costo</td>
			<td>Acciones</td>
		</tr>";
				$id_conteo = 0;
                $rowmaq = 0;
				while($resultm = mysqli_fetch_array($querym)){
					$id_conteo++;
					$template .= "
			<tr id=\"emaq{$rowmaq['id_estimacion_maquinaria']}\">
				<td>" . $id_conteo . "</td>
				<td>" . $resultm['descripcion_maquinaria'] . "</td>
				<td>" . $resultm['tiempo_uso'] . "</td>
				<td>" . $resultm['costo']* $resultm['tiempo_uso'] . "</td>
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
else
{
	$alert = "Necesitas seleccionar un proyecto válido y una subobra para agregar elementos";
	$code = 10;
}

echo $alert;
?>