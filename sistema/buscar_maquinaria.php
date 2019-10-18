<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }	
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Buscar maquinaria</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	    <section id="container">
		
	<?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: maquinaria.php");
        }
        
    ?>
	
		<br>
		<h1>Maquinaria</h1>
        <a href="crear_maquinaria.php" class="btn_new"><i class="fas fa-plus-circle"></i> Nueva maquinaria</a>
		
		<form action="buscar_maquinaria.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $_GET['busqueda']; ?>">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<th>Código</th>
				<th>Descripción</th>
				<th>Unidad</th>
				<th>Costo</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                include "conexion.php";
                $busqueda = $_GET['busqueda'];
				$search = array("ñ","Ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
				$replace = array("n","N","a","A","e","E","i","I","o","O","u","U");
				$busqueda = str_replace($search, $replace, $busqueda);
				$busqueda = mysqli_real_escape_string($conection, $busqueda);           
                $sql_registrar = mysqli_query($conection,"SELECT COUNT(*) as total_registro, u.descripcion, ti.descripcion_tipo_insumo
															FROM maquinaria m
																LEFT JOIN unidades u
																ON (u.id_unidad=m.id_unidad)
																LEFT JOIN tipoinsumo ti
																ON (m.id_tipo_insumo=ti.id_tipo_insumo)
                                                            WHERE (codigo_maquinaria LIKE '%$busqueda%' or
                                                                   descripcion_maquinaria LIKE '%$busqueda%' or
																   ti.descripcion_tipo_insumo LIKE '%$busqueda%' or
																   u.descripcion LIKE '%$busqueda%' or
																   costo_maquinaria LIKE '%$busqueda%')
															AND m.estatus=1
											")or die(mysqli_error());
				
                $result_register = mysqli_fetch_array($sql_registrar);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 5;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$query = mysqli_query($conection,"SELECT m.*, u.descripcion, ti.descripcion_tipo_insumo
													FROM maquinaria m
														LEFT JOIN unidades u
														ON (u.id_unidad=m.id_unidad)
														LEFT JOIN tipoinsumo ti
														ON (m.id_tipo_insumo=ti.id_tipo_insumo)
                                                    WHERE (codigo_maquinaria LIKE '%$busqueda%' or
                                                           descripcion_maquinaria LIKE '%$busqueda%' or
														   ti.descripcion_tipo_insumo LIKE '%$busqueda%' or
														   u.descripcion LIKE '%$busqueda%' or
														   costo_maquinaria LIKE '%$busqueda%')
													AND m.estatus=1
									");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) {
						$data["codigo_maquinaria"] = htmlspecialchars($data["codigo_maquinaria"]);
			?>
                        <tr>
                            <td><?php echo $data["codigo_maquinaria"]; ?></td>
                            <td><?php echo $data["descripcion_maquinaria"]; ?></td>
							<td><?php echo $data["descripcion"]; ?></td>
							<td><?php echo "$".number_format($data["costo_maquinaria"],2,".",","); ?></td>
                            <td>
                                <a class="link_edit" href="editar_maquinaria.php?id=<?php echo $data["codigo_maquinaria"];?>"><i class="fas fa-user-edit"></i> Editar</a>
                                |
                                <a  class="link_eliminar" href="eliminar_confirmar_maquinaria.php?id=<?php echo $data["codigo_maquinaria"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
		<?php
					}
				}
			 ?>
		</table>
		<?php
            if($total_registro != 0)
            {
        ?>
		<div class="paginador">
		    <ul>
            <?php
                if($pagina !=1){  
            ?>
		        <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda;?>">|<</a></li>
		        <li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda;?>"><<</a></li>
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
		<?php 
            }
        ?>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>