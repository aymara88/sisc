<?php
require_once "includes/verifica_sesion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Listado de Clientes</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	    <section id="container">
		
	<?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: lista_clientes.php");
        }
        
    ?>
	
		<br>
		<h1>Lista de Clientes</h1>
		<a href="registro_cliente.php" class="btn_new">Crear Cliente</a>
		
		<form action="buscar_cliente.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $_GET['busqueda']; ?>">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<tr>
				<th>ID</th>
				<th>Nombre(s)</th>
				<th>Tipo Persona</th>
				<th>RFC Cliente</th>
				<th>Domicilio</th>
				<th>Teléfono</th>
				<th>Email</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                include "conexion.php";
                $busqueda = $_GET['busqueda'];
				$search = array("ñ","Ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
				$replace = array("n","N","a","A","e","E","i","I","o","O","u","U");
				$busqueda = str_replace($search, $replace, $busqueda);
				$busqueda = mysqli_real_escape_string($conection, $busqueda);             
                $sql_registrar = mysqli_query($conection,"SELECT COUNT(*) as total_registro 
                                                            FROM clientes c INNER JOIN tipo_persona t ON t.id_tipo_persona = c.id_tipo_persona
                                                                            INNER JOIN localidades l ON l.id_localidad = c.id_localidad
                                                                            INNER JOIN municipios m ON m.id_municipio = l.id_municipio
                                                                            INNER JOIN estados e ON e.id_estado = m.id_estado 
                                                            WHERE (c.id_cliente LIKE '%$busqueda%' or
                                                                   c.nombre_cliente LIKE '%$busqueda%' or
                                                                   t.tipo_persona LIKE '%$busqueda%' or
                                                                   c.rfc_cliente LIKE '%$busqueda%' or
                                                                   e.nombre_estado LIKE '%$busqueda%' or
                                                                   m.nombre_municipio LIKE '%$busqueda%' or
                                                                   l.nombre_localidad LIKE '%$busqueda%' or
                                                                   c.colonia LIKE '%$busqueda%' or
                                                                   c.calle LIKE '%$busqueda%' or
                                                                   c.numero LIKE '%$busqueda%' or
                                                                   c.cp LIKE '%$busqueda%' or
                                                                   c.telefono LIKE '%$busqueda%' or
                                                                   c.email LIKE '%$busqueda%')      
                                                                   AND c.estatus = 1")or die(mysqli_error());
				
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
            
				$query = mysqli_query($conection,"SELECT c.id_cliente, c.nombre_cliente,t.tipo_persona, c.rfc_cliente, e.nombre_estado, m.nombre_municipio, l.nombre_localidad, c.colonia, c.calle, c.numero, c.cp, c.telefono, c.email
                                            FROM clientes c INNER JOIN tipo_persona t ON t.id_tipo_persona = c.id_tipo_persona
                                                INNER JOIN localidades l ON l.id_localidad = c.id_localidad
                                                INNER JOIN municipios m ON m.id_municipio = l.id_municipio
                                                INNER JOIN estados e ON e.id_estado = m.id_estado 
                                            WHERE (c.id_cliente LIKE '%$busqueda%' or
                                                    c.nombre_cliente LIKE '%$busqueda%' or
                                                    t.tipo_persona LIKE '%$busqueda%' or
                                                    c.rfc_cliente LIKE '%$busqueda%' or
                                                    e.nombre_estado LIKE '%$busqueda%' or
                                                    m.nombre_municipio LIKE '%$busqueda%' or
                                                    l.nombre_localidad LIKE '%$busqueda%' or
                                                    c.colonia LIKE '%$busqueda%' or
                                                    c.calle LIKE '%$busqueda%' or
                                                    c.numero LIKE '%$busqueda%' or
                                                    c.cp LIKE '%$busqueda%' or
                                                    c.telefono LIKE '%$busqueda%' or
                                                    c.email LIKE '%$busqueda%')      
                                            AND c.estatus = 1 ORDER BY c.id_cliente ASC LIMIT $desde,$por_pagina");				
				mysqli_close($conection);
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
						<a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="editar_cliente.php?id=<?php echo $data["id_cliente"]; ?>"><i class="fas fa-user-edit"></i> Editar</a>
						<?php if($data["id_cliente"] != 1){ ?>
						<a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="eliminar_confirmar_cliente.php?id=<?php echo $data["id_cliente"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
						<?php  } ?>
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