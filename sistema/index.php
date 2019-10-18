<?php 
require_once "includes/verifica_sesion.php";
require_once('includes/estadisticas.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Sistema Web para el Control y Gestión de Proyectos de Construcción</title>
</head>
<body>

	<?php  include "includes/header.php";?>
	<section id="container">
		<br>
		<h1>Bienvenidos</h1>
		<br>
		<br>
		<table>
			<tr>
				<th colspan="5">Estadísticas</th>
			</tr>
			<tr>
				<td>
					<i class="fas fa-users fa-4x" style="color: #cbcbff;">&nbsp;</i>
					<span style="display:block;">Usuarios Activos</span>
					<span style="display: block;color: #49779d;font-size: 22px;font-weight: bold;"><?php echo $estadisticas['usuarios']['USUARIOS_TOTAL']; ?></span>
				</td>
				<td>
					<i class="fas fa-user-tie fa-4x" style="color:#b03f74;">&nbsp;</i>
					<span style="display:block;">Clientes</span>
					<span style="display: block;color: #49779d;font-size: 22px;font-weight: bold;"><?php echo $estadisticas['clientes']['CLIENTES_TOTAL']; ?></span>
				</td>
				<td>
					<i class="fas fa-truck-container fa-4x" style="color:#6bbdac;">&nbsp;</i>
					<span style="display:block;">Proveedores</span>
					<span style="display: block;color: #49779d;font-size: 22px;font-weight: bold;"><?php echo $estadisticas['proveedores']['PROVEEDORES_TOTAL']; ?></span>
				</td>
				<td>
					<i class="fas fa-newspaper fa-4x" style="color:#6b90bd;">&nbsp;</i>
					<span style="display:block;">Proyectos</span>
					<span style="display: block;color: #49779d;font-size: 22px;font-weight: bold;"><?php echo $estadisticas['proyectos']['PROYECTOS_TOTAL']; ?></span>
				</td>
				<td>
					<i class="fal fa-ballot-check fa-4x" style="color:#bd926b;">&nbsp;</i>
					<span style="display:block;">Reportes</span>
					<span style="display: block;color: #49779d;font-size: 22px;font-weight: bold;"><?php echo $estadisticas['reportes']['REPORTES_TOTAL']; ?></span>
				</td>
			</tr>
		</table>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>