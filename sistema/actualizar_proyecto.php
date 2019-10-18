<?php
require_once "includes/verifica_sesion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Proyectos</title>
</head>
<body> 

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<i class="far fa-chalkboard-teacher fa-2x"></i>
		<h1>Proyectos</h1>
        <a href="crear_proyecto.php" class="btn_new"><i class="fas fa-address-card"></i> Crear Proyecto</a>
		
		<form action="buscar_proyecto.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Nombre del Proyecto</th>
				<th>Descripción</th>
				<th>Costo Estimado</th>
				<th>Duración Estimada</th>
				<th>Cliente</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                
                include "conexion.php";
                //paginador
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM obras");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 5;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$query = mysqli_query($conection,"SELECT o.*, c.nombre_cliente FROM obras o
													LEFT JOIN clientes c
													ON c.id_cliente=o.id_cliente
													WHERE 1=1
													ORDER BY o.id_proyecto ASC 
													LIMIT $desde,$por_pagina");	
 
				//mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) { 			
			?>
                        <tr>
                            <td><?php echo $data["id_proyecto"]; ?></td>
                            <td><?php echo $data["nombre_proyecto"]; ?></td>
                            <td><?php echo $data["descripcion_proyecto"]; ?></td>
                            <td><?php echo $data["costo_estimado_proyecto"]; ?></td>
                            <td><?php echo $data["duracion_proyecto"]; ?></td>
                            <td><?php echo $data["nombre_cliente"]; ?></td>

                            <td>
                                <a class="link_edit" href="editar_proyecto.php?id=<?php echo $data["id_proyecto"];?>"><i class="fas fa-user-edit"></i> Editar</a>
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