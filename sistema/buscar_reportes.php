<?php 
require_once "includes/verifica_sesion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Reportes</title>
</head>
<body> 

	<?php  
		include "includes/header.php";
        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: reportes.php");
        }
        
    ?>	
	<section id="container">
		<br>
		<h1>Reportes</h1>

		<form action="buscar_reportes.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $_GET['busqueda']; ?>">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
			
		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Costo Estimado</th>
				<th>Duracion</th>
				<th>Cliente</th>
				<th>Acciones</th>				
			</tr>
			
			<?php 
                
                include "conexion.php";
                //paginador
                $busqueda = $_GET['busqueda'];
				$search = array("ñ","Ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
				$replace = array("n","N","a","A","e","E","i","I","o","O","u","U");
				$busqueda = str_replace($search, $replace, $busqueda);
				$busqueda = mysqli_real_escape_string($conection, $busqueda);
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM obras 
															WHERE estatus=1 AND nombre_proyecto LIKE '%$busqueda%' or
															descripcion_proyecto LIKE '%$busqueda%'
											");
				
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 12;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$query = mysqli_query($conection,"SELECT o.*, c.nombre_cliente FROM obras o
					LEFT JOIN clientes c
					ON o.id_cliente=c.id_cliente
				WHERE o.estatus=1 AND o.nombre_proyecto LIKE '%$busqueda%' or
					o.descripcion_proyecto LIKE '%$busqueda%'
				ORDER BY id_proyecto ASC LIMIT $desde,$por_pagina");	
 
				//mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) {	
			?>
                        <tr>
							<td><?php echo (int)$data["id_proyecto"]; ?></td>
                            <td><?php echo htmlspecialchars($data["nombre_proyecto"]); ?></td>
                            <td><?php echo htmlspecialchars($data["descripcion_proyecto"]); ?></td>
							<td><?php echo "$".number_format($data["costo_estimado_proyecto"],2,".",","); ?></td>							
                            <td><?php echo (int)$data["duracion_proyecto"]; ?></td>
							<td><?php echo $data["nombre_cliente"]; ?></td>
                            <td>
                                <a class="link_edit" style="color:green;" href="exportar.php?action=excel&id=<?php echo $data["id_proyecto"];?>"><i class="fas fa-file-excel fa-lg"></i></a>&nbsp;
                                <a class="link_edit" style="color:red;" href="exportar.php?action=pdf&id=<?php echo $data["id_proyecto"];?>"><i class="fas fa-file-pdf fa-lg"></i></a>&nbsp;
                                <a class="link_edit" href="exportar.php?action=word&id=<?php echo $data["id_proyecto"];?>"><i class="fas fa-file-word fa-lg"></i></a>
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
		                echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                    }
					if($i >= $pagina + 25)
						break;					
		        }
                if($pagina !=$total_paginas){
		    ?>      
		        <li><a href="?pagina=<?php echo $pagina +1; ?>&busqueda=<?php echo $busqueda;?>">>></a></li>
		        <li><a href="?pagina=<?php echo $total_paginas; ?>&amp;busqueda=<?php echo $busqueda;?>">>|</a></li>
		    <?php 
                }
            ?>  
		    </ul>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>