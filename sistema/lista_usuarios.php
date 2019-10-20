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
		<br>
		<h1><i class="fas fa-id-card fa-lg"></i> Lista de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear Usuario</a>
		
		<form action="buscar_usuario.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>
		
  		<table>
			<tr>
				<th>ID</th>
				<th>Nombre(s)</th>
				<th>Apellido Paterno</th>
				<th>Apellido Materno</th>
				<th>Sexo</th>
				<th>Cargo</th>
				<th>Teléfono</th>
				<th>Email</th>
				<th>Login</th>
				<th>Rol</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
                
                include "conexion.php";
                //paginador
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM usuarios WHERE estatus = 1");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 8;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }
            
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
            
				$query = mysqli_query($conection,"SELECT u.id_usuario,u.nombre_usuario,u.apellido_pater_usuario,u.apellido_mater_usuario, s.descripcion_sexo,c.descripcion_cargo, u.telefono_celular, u.email, u.login_usuario, r.descripcion_rol FROM usuarios u INNER JOIN sexo s ON s.id_sexo = u.id_sexo INNER JOIN cargos c ON c.id_cargo = u.id_cargo INNER JOIN roles r ON r.id_rol = u.id_rol WHERE u.estatus = 1 ORDER BY u.id_usuario ASC LIMIT $desde,$por_pagina");				
				//mysqli_close($conection);
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
                                <a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i class="fas fa-user-edit"></i>&nbsp;Editar</a>
                                <?php if($data["id_usuario"] != 1){ ?>
                                <a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;" href="eliminar_confirmar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i class="fas fa-trash"></i>&nbsp;Eliminar</a>
                                <?php  } ?>
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