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
	<title>Materiales</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<h1><i class="far fa-paint-roller fa-lg"></i> Lista de materiales</h1>
        <a href="crear_material.php" class="btn_new"><i class="fas fa-plus-circle"></i> Nuevo material</a>

		<!--<form action="buscar_material.php" method="get" class="form_search">
		    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
		    <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
		</form>-->

        <form action="buscar_material_filtros.php" method="get" class="form_search">
            <label for="correo">Código</label>
            <input type="text" name="f_codigo" id="f_codigo" placeholder="Filtrar">

            <label for="correo">Unidad</label>
            <input type="text" name="f_unidad" id="f_unidad" placeholder="Filtrar">

            <label for="correo">Familia</label>
            <input type="text" name="f_familia" id="f_familia" placeholder="Filtrar">

            <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
        </form>

		<table>
			<tr>
				<th>Código</th>
				<th width="280">Descripción</th>
				<th>Unidad</th>
				<th>Familia</th>
				<th>Costo</th>
				<th>Proveedor</th>
				<th>Acciones</th>
			</tr>

			<?php

                include "conexion.php";
                //paginador
                $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM materiales WHERE estatus=1");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];
                $por_pagina = 20;

                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

				$query = mysqli_query($conection,"SELECT m.*, u.descripcion, ti.descripcion_tipo_insumo, f.familia, p.razon_social
				FROM materiales m
					LEFT JOIN unidades u
					ON (u.id_unidad=m.id_unidad)
					LEFT JOIN tipoinsumo ti
					ON (m.id_tipo_insumo=ti.id_tipo_insumo)
					LEFT JOIN familias f
					ON (m.id_familia=f.id_familia)
					LEFT JOIN proveedores p
					ON (m.id_proveedor=p.id_proveedor)
				WHERE m.estatus=1
				ORDER BY codigo_material ASC LIMIT $desde,$por_pagina");

				//mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if ($result > 0) {

					while ($data = mysqli_fetch_array($query)) {
					$data["codigo_material"] = htmlspecialchars($data["codigo_material"]);
			?>
                        <tr>
                            <td><?php echo $data["codigo_material"]; ?></td>
                            <td><?php echo $data["descripcion_material"]; ?></td>
							<td><?php echo $data["descripcion"]; ?></td>
							<td><?php echo $data["familia"]; ?></td>
							<td><?php echo "$".number_format($data["costo_material"],2,".",","); ?></td>
							<td><?php echo $data["razon_social"]; ?></td>
                            <td>
                                <a class="link_edit" href="editar_material.php?id=<?php echo $data["codigo_material"];?>"><i class="fas fa-user-edit"></i> Editar</a>
                                |
                                <a  class="link_eliminar" href="eliminar_confirmar_material.php?id=<?php echo $data["codigo_material"]; ?>"><i class="fas fa-trash"></i> Eliminar</a>
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