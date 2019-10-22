<?php
require_once "includes/verifica_sesion.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Lista de Clientes</title>
</head>
<body> 

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<h1><i class="fas fa-id-badge fa-lg"></i>Lista de Clientes</h1>
        <a href="registro_cliente.php" class="btn_new"><i class="fas fa-address-card"></i> Crear Cliente</a>
		
		<form action="buscar_cliente.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Nombre o Razón Social</th>
				<th>Tipo Persona</th>
				<th>RFC Cliente</th>
				<th>Domicilio</th>
				<th>Teléfono</th>
				<th>Email</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                
                include "conexion.php";
                //paginador
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM clientes WHERE estatus = 1");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 4;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$query = mysqli_query($conection,"SELECT c.id_cliente, c.nombre_cliente, t.id_tipo_persona, t.tipo_persona, c.rfc_cliente, e.id_estado, e.nombre_estado, m.id_municipio, m.nombre_municipio, l.id_localidad, l.nombre_localidad, c.colonia, c.calle, c.numero, c.cp, c.telefono, c.email 
					FROM clientes c INNER JOIN tipo_persona t ON t.id_tipo_persona = c.id_tipo_persona
					                INNER JOIN localidades l ON l.id_localidad = c.id_localidad
					                INNER JOIN municipios m ON m.id_municipio = l.id_municipio
					                INNER JOIN estados e ON e.id_estado = m.id_estado
					WHERE c.estatus = 1 ORDER BY c.id_cliente ASC LIMIT $desde,$por_pagina");	
 
				//mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) {
						$domicilio = $data["calle"].' '.$data["numero"].', '.$data["colonia"].', '.strtoupper($data["nombre_localidad"]).', '.strtoupper($data["nombre_municipio"]).', '.strtoupper($data["nombre_estado"]);
						 			
			?>
                        <tr>
                            <td><?php echo $data["id_cliente"]; ?></td>
                            <td><?php echo $data["nombre_cliente"]; ?></td>
                            <td><?php echo $data["tipo_persona"]; ?></td>
                            <td><?php echo $data["rfc_cliente"]; ?></td>
                            <td><?php echo $domicilio; ?></td>
                            <td><?php echo $data["telefono"]; ?></td>
                            <td><?php echo $data["email"]; ?></td>

                            <td>
                                <a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="editar_cliente.php?id=<?php echo $data["id_cliente"];?>"><i class="fas fa-user-edit"></i> Editar</a>
                                <a  class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="eliminar_confirmar_cliente.php?id=<?php echo $data["id_cliente"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
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