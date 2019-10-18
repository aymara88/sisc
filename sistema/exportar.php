<?php 
require_once "includes/verifica_sesion.php";
include "conexion.php";
if(isset($_GET['id']))
	$id_proyecto = (int)$_GET['id'];
else
	$id_proyecto = 0;
if($id_proyecto == 0)
	die('No puedes acceder a esta función directamente...');
$server_path = explode(".php", $_SERVER['HTTP_REFERER']);
$server_path = $server_path[0];
$query_proyecto = mysqli_query($conection,"SELECT o.*, c.nombre_cliente FROM obras o
											LEFT JOIN clientes c
											ON o.id_cliente=c.id_cliente
										WHERE o.estatus=1 AND id_proyecto='$id_proyecto'
							");
							
while($resultado_proyecto = mysqli_fetch_array($query_proyecto))
{
	$fecha = $resultado_proyecto['fecha'];
	$nombre_proyecto = $resultado_proyecto['nombre_proyecto'];
	$descripcion_proyecto = $resultado_proyecto['descripcion_proyecto'];
	$costo_proyecto = "$".number_format($resultado_proyecto['costo_estimado_proyecto'],2,".",",");
	$duracion_proyecto = $resultado_proyecto['duracion_proyecto'];
	if($duracion_proyecto == 1)
		$mes = " mes";
	else
		$mes = " meses";
	$nombre_cliente = $resultado_proyecto['nombre_cliente'];
}
$attachment_name = str_replace(" ", "", $nombre_proyecto);
if($_GET['action'] == "excel")
{
	$attachment_name .= ".xls";		
	$headers = "<meta http-equiv=Content-Type content=\"application/vnd.ms-excel\">";		
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$attachment_name");
	header("Pragma: no-cache");
	header("Expires: 0");
}
else if($_GET['action'] == "pdf")
{
	$attachment_name .= ".pdf";
	$headers = "<meta http-equiv=Content-Type content=\"application/pdf\">";
	require_once("includes/lib/autoload.inc.php");
	header("Content-Disposition: attachment; filename=$attachment_name");
	header("Pragma: no-cache");
	header("Expires: 0");
}
else if($_GET['action'] == "word")
{
	$attachment_name .= ".doc";		
	$headers = "<meta http-equiv=Content-Type content=\"application/vnd.ms-word\">";
	header("Content-type:application/vnd.ms-word");
	header("Content-Disposition: attachment; filename=$attachment_name");
	header("Pragma: no-cache");
	header("Expires: 0");
}
else if($_GET['action'] == "vista_previa")
{
	$headers = "";
	$attachment_name = "";		
}
else
{
	die("No se puede ejecutar esta acción en el servidor");
}

function GetDatosSubObra($id_sub_obra)
{
	global $conection;
	$id_sub_obra = (int)$id_sub_obra;
	if($id_sub_obra == 0)
		return false;
	// Lista de Herramientas
	$datos2 = 0;
	$query_herramientas = mysqli_query($conection,"SELECT eh.*, h.descripcion_herramienta FROM estimacion_herramienta eh
											LEFT JOIN herramientas h
											ON eh.codigo_herramientas=h.codigo_herramienta
											WHERE eh.estatus=1 AND eh.id_sub_obra = '$id_sub_obra' 
											ORDER BY eh.id_estimacion_herramienta
							");
	$resultado_herramientas = mysqli_num_rows($query_herramientas);
	$html['herramienta'] = "<table border=\"0\" width=\"100%\" id=\"herramientas\">
	<tr>
		<td colspan=\"4\" style=\"background:#d9c3ec;\">Herramientas</td>
	</tr>	
	<tr>
		<td width=\"46%\">Descripción</td>
		<td width=\"18%\">Tiempo de Uso</td>
		<td width=\"18%\">Costo por Uso</td>
		<td width=\"18%\">Costo Total</td>
	</tr>";
	$total_herramientas = 0;
	while($rowh = mysqli_fetch_array($query_herramientas))
	{
		$datos2 = 1;
		$total_herramientas += floatval($rowh['tiempo_uso'] * $rowh['costo']);
		$html['herramienta'] .= "
		<tr>
			<td>" . $rowh['descripcion_herramienta'] . "</td>
			<td>" . $rowh['tiempo_uso'] . "</td>
			<td>" . "$".number_format($rowh['costo'],2,".",",") . "</td>
			<td>" . "$".number_format($rowh['tiempo_uso'] * $rowh['costo'],2,".",",") . "</td>
		</tr>
		";
	}
		$html['herramienta'] .= "
		<tr style=\"background:none !important\">
			<td colspan=\"2\">&nbsp;</td>
			<td class=\"subtotal\">SubTotal</td>
			<td class=\"subtotal\">" . "$".number_format($total_herramientas,2,".",",") . "</td>
		</tr>";
	if($datos2 == 0)
	{
		$html['herramienta'] .= "<tr>
		<td colspan=\"4\">No hay herramientas agregadas a la subobra actual.</td>
	</tr>";
	}		
	$html['herramienta'] .= "</table>";
	
	// Lista de la mano de obra
	$datos3 = 0;
	$total_mano_obra = 0;
	$query_mano_obra = mysqli_query($conection,"SELECT em.*, mo.descripcion_mano_obra FROM estimacion_mano_obra em
												LEFT JOIN mano_obra mo
												ON em.codigo_mano_obra=mo.codigo_mano_obra
												WHERE em.estatus=1 AND em.id_sub_obra = '$id_sub_obra'
												ORDER BY em.id_estimacion_manobra
									");
	$resultado_mano_obra = mysqli_num_rows($query_mano_obra);
	$html['mano_obra'] = "<table border=\"0\" width=\"100%\" id=\"mano_obras\">
	<tr>
		<td colspan=\"4\" style=\"background:#d9c3ec;\">Mano de Obra</td>
	</tr>	
	<tr>
		<td width=\"46%\">Descripción</td>
		<td width=\"18%\">Tiempo de Uso</td>
		<td width=\"18%\">Costo por Jornada</td>
		<td width=\"18%\">Costo Total</td>
	</tr>";
	while($rowm = mysqli_fetch_array($query_mano_obra))
	{
		$datos3 = 1;
		$total_mano_obra += floatval($rowm['tiempo_uso'] * $rowm['costo']);
		$html['mano_obra'] .= "
		<tr>
			<td>" . $rowm['descripcion_mano_obra'] . "</td>
			<td>" . $rowm['tiempo_uso'] . "</td>
			<td>" . "$".number_format($rowm['costo'],2,".",",") . "</td>
			<td>" . "$".number_format($rowm['tiempo_uso'] * $rowm['costo'],2,".",",") . "</td>
		</tr>
		";
	}
		$html['mano_obra'] .= "
		<tr style=\"background:none !important\">
			<td colspan=\"2\">&nbsp;</td>
			<td class=\"subtotal\">SubTotal</td>
			<td class=\"subtotal\">" . "$".number_format($total_mano_obra,2,".",",") . "</td>
		</tr>";	
	if($datos3 == 0)
	{
		$html['mano_obra'] .= "<tr>
		<td colspan=\"4\">No hay mano de obra actualmente en esta subobra</td>
	</tr>";
	}		
	$html['mano_obra'] .= "</table>";
	
	// Lista de la maquinaria
	$datos4 = 0;
	$total_maquinaria = 0;
	$query_maquinaria = mysqli_query($conection,"SELECT em.*, m.descripcion_maquinaria FROM estimacion_maquinaria em
											LEFT JOIN maquinaria m
											ON em.codigo_maquinaria=m.codigo_maquinaria
											WHERE em.estatus=1 AND em.id_sub_obra = '$id_sub_obra'
											ORDER BY em.id_estimacion_maquinaria
							");
	$resultado_maquinaria = mysqli_num_rows($query_maquinaria);
	$html['maquinaria'] = "<table border=\"0\" width=\"100%\" id=\"maquinaria\">
	<tr>
		<td colspan=\"4\" style=\"background:#d9c3ec;\">Maquinaria</td>
	</tr>	
	<tr>
		<td width=\"46%\">Descripción</td>
		<td width=\"18%\">Tiempo de Uso</td>
		<td width=\"18%\">Costo por hora</td>
		<td width=\"18%\">Costo Total</td>
	</tr>";
	while($rowmaq = mysqli_fetch_array($query_maquinaria))
	{
		$datos4 = 1;
		$total_maquinaria += floatval($rowmaq['tiempo_uso'] * $rowmaq['costo']);
		$html['maquinaria'] .= "
		<tr>
			<td>" . $rowmaq['descripcion_maquinaria'] . "</td>
			<td>" . $rowmaq['tiempo_uso'] . "</td>
			<td>" . "$".number_format($rowmaq['costo'],2,".",",") . "</td>
			<td>" . "$".number_format($rowmaq['tiempo_uso'] * $rowmaq['costo'],2,".",",") . "</td>
		</tr>
		";
	}
		$html['maquinaria'] .= "<tr style=\"background:none !important\">
			<td colspan=\"2\">&nbsp;</td>
			<td class=\"subtotal\">SubTotal</td>
			<td class=\"subtotal\">" . "$".number_format($total_maquinaria,2,".",",") . "</td>
		</tr>";
	
	if($datos4 == 0)
	{
		$html['maquinaria'] .= "<tr>
		<td colspan=\"4\">No hay maquinaria agregada para esta subobra</td>
	</tr>";
	}		
	$html['maquinaria'] .= "</table>";
	
	// Lista de la materiales
	$datos5 = 0;
	$total_material = 0;
	$query_material = mysqli_query($conection,"SELECT em.*, m.descripcion_material FROM estimacion_material em
											LEFT JOIN materiales m
											ON em.codigo_material=m.codigo_material	
											WHERE em.estatus=1 AND em.id_sub_obra = '$id_sub_obra'
											ORDER BY em.id_estimacion_material
							");
	$resultado_material = mysqli_num_rows($query_material);
	$html['material'] = "<table border=\"0\" width=\"100%\" id=\"materiales\">
	<tr>
		<td colspan=\"4\" style=\"background:#d9c3ec;\">Materiales</td>
	</tr>	
	<tr>
		<td width=\"46%\">Descripción</td>
		<td width=\"18%\">Duración</td>
		<td width=\"18%\">Costo Unitario</td>
		<td width=\"18%\">Costo Total</td>
	</tr>";
	while($rowmat = mysqli_fetch_array($query_material))
	{
		$datos5 = 1;
		$total_material += floatval($rowmat['cantidad_usar'] * $rowmat['costo']);
		$html['material'] .= "
		<tr>
			<td>" . $rowmat['descripcion_material'] . "</td>
			<td>" . $rowmat['cantidad_usar'] . "</td>
			<td>" . "$".number_format($rowmat['costo'],2,".",",") . "</td>
			<td>" . "$".number_format($rowmat['cantidad_usar'] * $rowmat['costo'],2,".",",") . "</td>
		</tr>
		";
	}
		$html['material'] .= "<tr style=\"background:none !important\">
			<td colspan=\"2\">&nbsp;</td>
			<td class=\"subtotal\">SubTotal</td>
			<td class=\"subtotal\">" . "$".number_format($total_material,2,".",",") . "</td>
		</tr>";
	if($datos5 == 0)
	{
		$html['material'] .= "<tr>
		<td colspan=\"4\">No hay materiales agregados a esta subobra</td>
	</tr>";
	}		
	$html['material'] .= "</table>";
	return $html;	
}	

$page = '<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<meta http-equiv=Content-Disposition content="attachment; filename='.$attachment_name.'">
	<meta http-equiv=Content-Type content="application/pdf">
	<title>Exportar Proyecto</title>
	<style type="text/css">
		@charset "utf-8";
		@import url('.$server_path.'/fonts/GothamBook.css);
		@import url('.$server_path.'/fonts/GothamBold.css);
		* {margin: 0;padding: 0;box-sizing: border-box}
		.precio,.duracion,.cliente{font-size: 15px;}
		#contenedor{text-align:center;}
		table th {text-align: center;padding: 10px;background: #A463C3;color: #FFF}
		table td {text-align:left;padding:10px}
		table {border-collapse: collapse;font-size: 13pt;width: 90%;min-width: 1024px;max-width: 1024px;margin: auto}
		table tr:nth-child(2n+1) {background: #ededed}
		.subtotal{background: #465ea2;color: #ffffff}
		.logo_empresa{float:left}
		.slogan_empresa{float:right;margin-top: 20px}
		.cabecera{display:block;margin-top:50px}
		.inicio{height:90px;display:block;margin-top:50px;background:none !important;}
		body{font-size: 13pt;font-family: \'GothamBook\';width:90%;max-width: 1024px;min-width: 1024px;margin: auto}
	</style>
</head>
<body>
	<section id="contenedor">
		<table border="0">
			<tr style="background:none !important">
				<td width="30%" height="70" colspan="2"><img src="'.$server_path.'/frontend/imagenes/logo_ocamcas2.png" alt="Logo Empresa" /></td>
				<td width="70%" height="70" valign="middle" colspan="2">Constructora OCAMCAS, una nueva esperanza S.A de C.V.</td>
			</tr>
			<tr style="background:none !important">
				<td colspan="3" width="78%"><h2>REPORTE GENERAL DE PROYECTO DE CONSTRUCCIÓN</h2></td>
				<td width="22%">'.$fecha.'</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<th colspan="4">DATOS GENERALES DEL PROYECTO</th>
			</tr>
			<tr>
				<td width="20%">Nombre del Proyecto</td>
				<td width="80%" colspan="3">'.$nombre_proyecto.'</td>
			</tr>
			<tr>
				<td width="20%">Descripción</td>
				<td width="80%" colspan="3">'.$descripcion_proyecto.'</td>
			</tr>
			<tr>
				<td width="20%">Costo Total</td>
				<td width="80%" colspan="3">'.$costo_proyecto.'</td>
			</tr>	
			<tr>
				<td width="20%">Duración Total (Meses)</td>
				<td width="80%" colspan="3">'.$duracion_proyecto . $mes.'</td>
			</tr>
		</table>
		<br />';
		$idsubobra = 1;		
		$query = mysqli_query($conection,"SELECT * FROM subobras WHERE estatus=1 AND id_proyecto='$id_proyecto' ORDER BY id_sub_obra");
 		$result = mysqli_num_rows($query);
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) 
			{
				$id_sub_obra = (int)$data['id_sub_obra'];
				$duracion_subobra = (float)$data["duracion"];
				if($duracion_subobra == 1)
					$mes = " mes";
				else
					$mes = " meses";
				$nombre_sub_obra = htmlspecialchars($data["nombre_sub_obra"]);
				$datos_sub_obra = GetDatosSubObra($id_sub_obra);
				$desc_sub_obra = htmlspecialchars($data["descripcion_subobra"]);
				$costo_sub_obra = number_format($data["costo_estimado"],2,".",",");
                $page .= '<table border="0" width="100%">
						<tr>
							<th colspan="4">SUBOBRA '.$idsubobra.' </th>
						</tr>			
                        <tr>
                            <td>Subobra '.$idsubobra.'</td>
                            <td colspan="3">'.htmlspecialchars($data["nombre_sub_obra"]).'</td>							
						</tr>
                        <tr>
                            <td>Decripción</td>
                            <td colspan="3">'. htmlspecialchars($data["descripcion_subobra"]).'</td>							
						</tr>	
 						<tr>
							<td>Costo</td>
							<td colspan="3">$'.number_format($data["costo_estimado"],2,".",",").'</td>
						</tr>
						<tr>
							<td>Duración</td>
                            <td colspan="3">'.$duracion_subobra . $mes .'</td>
                        </tr>
					</table>
					'.$datos_sub_obra['herramienta'].'
					'.$datos_sub_obra['mano_obra'].'
					'.$datos_sub_obra['maquinaria'].'
					'.$datos_sub_obra['material'].'
					<br />';
					$idsubobra++;
			}
		}
		$page .= '</section>';
	include "includes/footer.php";
$page .= '</body>
</html>';

if($_GET['action'] == "excel")
{
	echo $page;
}
else if($_GET['action'] == "pdf")
{
	ini_set("memory_limit", "64M");
	$options = new Dompdf\Options();
	$options->set(array('isPhpEnabled'          => true, 
						'isRemoteEnabled'       => true, 
						'isJavascriptEnabled'   => false, 
						'isHtml5ParserEnabled'  => true, 
						'tempDir'               => sys_get_temp_dir())
			);
	$dompdf = new Dompdf\Dompdf();
	$dompdf->setOptions($options);    
	$dompdf->loadHtml($page);    
	$dompdf->setPaper('A4', 'landscape');            
	$dompdf->render();
	$dompdf->stream($attachment_name);
	exit(0);
}
else if($_GET['action'] == "word")
{
	echo $page;
}
else if($_GET['action'] == "vista_previa")
{
	echo $page;
}