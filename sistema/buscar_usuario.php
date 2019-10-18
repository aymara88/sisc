<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1){
            header("location: ./");
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta lang="es">
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Lista de Usuarios</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	    <section id="container">
		
	<?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: lista_usuarios.php");
        }
        
    ?>
	
		<br>
		<h1>Lista de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear Usuario</a>
		
		<form action="buscar_usuario.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $_GET['busqueda']; ?>">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Nombre(s)</th>
				<th>Apellido Paterno</th>
				<th>Apellido Materno</th>
				<th>Id Sexo</th>
				<th>Id Cargo</th>
				<th>Teléfono</th>
				<th>Email</th>
				<th>Login</th>
				<th>Id Rol</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                include "conexion.php";
            
                //paginador
                $rol = '';
                if($busqueda == 'administrador'){
                    $rol = " or id_rol LIKE '%1%' ";
                }else if($busqueda == 'supervisor'){
                    $rol = " or id_rol LIKE '%2%'";
                }
                $busqueda = $_GET['busqueda'];
				$search = array("ñ","Ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
				$replace = array("n","N","a","A","e","E","i","I","o","O","u","U");
				$busqueda = str_replace($search, $replace, $busqueda);
				$busqueda = mysqli_real_escape_string($conection, $busqueda);			
                $sql_registrar = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM usuarios 
                                                                WHERE (id_usuario LIKE '%$busqueda%' or
                                                                        nombre_usuario LIKE '%$busqueda%' or
                                                                        apellido_pater_usuario LIKE '%$busqueda%' or
                                                                        apellido_mater_usuario LIKE '%$busqueda%' or
                                                                        telefono_celular LIKE '%$busqueda%' or
                                                                        email LIKE '%$busqueda%' or
                                                                        login_usuario LIKE '%$busqueda%'
                                                                        $rol)
                                                                AND estatus = 1");
				
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
            
				$query = mysqli_query($conection,"SELECT u.id_usuario,u.nombre_usuario,u.apellido_pater_usuario,u.apellido_mater_usuario, s.descripcion_sexo,c.descripcion_cargo, u.telefono_celular, u.email, u.login_usuario, r.descripcion_rol FROM usuarios u INNER JOIN sexo s ON s.id_sexo = u.id_sexo INNER JOIN cargos c ON c.id_cargo = u.id_cargo INNER JOIN roles r ON r.id_rol = u.id_rol 
                WHERE (u.id_usuario LIKE '%$busqueda%' or
                        u.nombre_usuario LIKE '%$busqueda%' or
                        u.apellido_pater_usuario LIKE '%$busqueda%' or
                        u.apellido_mater_usuario LIKE '%$busqueda%' or
                        u.telefono_celular LIKE '%$busqueda%' or
                        u.email LIKE '%$busqueda%' or
                        u.login_usuario LIKE '%$busqueda%' or
                        r.descripcion_rol LIKE '%$busqueda%')
                AND
                estatus = 1 ORDER BY u.id_usuario ASC LIMIT $desde,$por_pagina");				
				mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {
					
					while ($data = mysqli_fetch_array($query)) {
						
			?>
				<tr>
					<td><?php echo $data["id_usuario"]; ?></td>
					<td><?php echo $data["nombre_usuario"]; ?></td>
					<td><?php echo $data["apellido_pater_usuario"]; ?></td>
					<td><?php echo $data["apellido_mater_usuario"]; ?></td>
					<td><?php echo $data["descripcion_sexo"]; ?></td>
					<td><?php echo $data["descripcion_cargo"]; ?></td>
					<td><?php echo $data["telefono_celular"]; ?></td>
					<td><?php echo $data["email"]; ?></td>
					<td><?php echo $data["login_usuario"]; ?></td>
					<td><?php echo $data["descripcion_rol"]; ?></td>
					<td>
						<a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i class="fas fa-user-edit"></i> Editar</a>
						<?php if($data["id_usuario"] != 1){ ?>
						<a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="eliminar_confimar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
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