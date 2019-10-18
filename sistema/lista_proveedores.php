<?php
require_once "includes/verifica_sesion.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Lista de Proveedores</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<h1><i class="fas fa-truck-container"></i> Lista de Proveedores</h1>
		<a href="registro_proveedor.php" class="btn_new"><i class="fas fa-plus-circle"></i> Agregar Proveedor</a>
		
		<form action="buscar_proveedor.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Razon Social</th>
				<th>Tipo de Persona</th>
				<th>RFC</th>
				<th>Calle</th>
				<th>Número</th>
				<th>Colonia</th>
				<th>Código Postal</th>
			<!--<th>ID Localidad</th>  -->
				<th>Teléfono</th>
				<th>Correo Electrónico</th>
				<th>Página Web</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                
                include "conexion.php";
                //paginador
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registros FROM proveedores WHERE estatus=1");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registros'];
                $por_pagina = 8;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$id_usuario = (int)$_SESSION['id_usuario'];
				$query = mysqli_query($conection,"SELECT * FROM proveedores WHERE estatus=1 ORDER BY id_proveedor ASC LIMIT $desde,$por_pagina");	
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) {
						$data['id_tipo_persona'] = (int)$data['id_tipo_persona'];
						$tipo_personaq = mysqli_query($conection, "SELECT tipo_persona FROM tipo_persona WHERE id_tipo_persona = '{$data['id_tipo_persona']}' LIMIT 1");
						$tipo_personar = mysqli_fetch_assoc($tipo_personaq);
						$data['tipo_persona'] = $tipo_personar['tipo_persona'];
						if(empty($data['tipo_persona']))
							$data['tipo_persona'] = "FISICA";
						
			?>
                        <tr>
                            <td><?php echo $data["id_proveedor"]; ?></td>
                            <td><?php echo $data["razon_social"]; ?></td>
							<td><?php echo $data['tipo_persona']; ?></td>
                            <td><?php echo $data["rfc"]; ?></td>
                            <td><?php echo $data["calle"]; ?></td>
                            <td><?php echo $data["numero"]; ?></td>
                            <td><?php echo $data["colonia"]; ?></td>
                            <td><?php echo $data["cp"]; ?></td>
                            
                            <td><?php echo $data["telefono"]; ?></td>
                            <td><?php echo $data["mail"]; ?></td>
							<td><?php echo $data["pagina_web"]; ?></td>
                            <td>
                                <a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="editar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>"><i class="fas fa-user-edit"></i> Editar</a>
                                <a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="eliminar_confirmar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
			<?php
					}
				}
            ?>
		</table>
		<div class="paginador">
		    <ul>
            <?php
                if($pagina !=1){  
            ?>
		        <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
		        <li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-chevron-circle-left fa-lg"></i></a></li>
		    <?php
                }
		        for($i=$pagina; $i <= $total_paginas; $i++){
                    if($i == $pagina){
                        echo '<li class="pageSelected">'.$i.'</li>';
                    }else{
		                echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                    }
					if($i >= $pagina + 25)
						break;
		        }
                if($pagina != $total_paginas){
		    ?>      
		        <li><a href="?pagina=<?php echo $pagina +1; ?>"><i class="fas fa-chevron-circle-right fa-lg"></i></a></li>
		        <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
		    <?php 
                }
            ?>      
		    </ul>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>