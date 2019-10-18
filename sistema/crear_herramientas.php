<?php 
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }

	include "conexion.php";
	include "frontend/encriptacion.php";
    require_once('frontend/CrudUsuario.php');

    if(isset($_POST['btn-signup'])){
        $alert='';
        $herramientas = strtoupper($_POST['codigo']);
		$descripcion = strtoupper($_POST['descripcion']);
		$tipo_insumo = $_POST['tipo_insumo'];
		$unidad = (int)$_POST['unidad'];
		$costo = (float)$_POST['costo'];
		
        if(empty($herramientas)){
            $alert = "Introduce el código!";
            $code = 1;
        }else if(empty($descripcion)){
            $alert = "Necesitas agregar una descripción";
            $code = 2;
        }else if(!is_numeric($costo)){
            $alert = "El costo sólo puede tener números!";
            $code = 3;
        }else if(!is_numeric($tipo_insumo)){
            $alert = "La descripción sólo puede tener números!";
            $code = 4;
        }else if(!is_numeric($unidad)){
            $alert = "La unidad sólo puede tener números!";
            $code = 5;
        }else{
			$herramientas = mysqli_real_escape_string($conection, $herramientas);
			$descripcion = mysqli_real_escape_string($conection, $descripcion);
			$tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
			$unidad = mysqli_real_escape_string($conection, $unidad);
			$costo = mysqli_real_escape_string($conection, $costo);		
            $query = mysqli_query($conection, "SELECT * FROM herramientas WHERE codigo_herramienta='$herramientas'");
            $result = mysqli_fetch_array($query);
			if ($result > 0) {
                $alert = "La herramienta que intentas crear ya existe!";
                $code = 8;
			}else{
                $query_insert = mysqli_query($conection,"INSERT INTO herramientas(codigo_herramienta, descripcion_herramienta, id_tipo_insumo, id_unidad, costo_herramienta) VALUES('$herramientas','$descripcion','$tipo_insumo', '$unidad', '$costo')");
				
                if ($query_insert){                    
                    $alert="herramienta creada correctamente!";
                    $code = 9;
                   
                }else{
                    $alert = "Error al crear herramienta!";
                    $code = 10;
				}
            }
        }						
    }  
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Nueva herramientas</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	<section id="container">
		<div class="form_register">
			<br>
			<h1><i class="fas fa-tools fa-lg"></i> Crear nueva herramienta</h1>
			<hr>
			<?php if(isset($alert)){ ?>
			<div class="alert"><?php echo $alert; ?>
			</div>
			<?php 
                } 
            ?>
				<form action="" method="post" name="miForm">
					<div class="divisor_resp">
						<label for="codigo">Código</label>
						<input type="text" name="codigo" id="codigo" maxlength="30" required title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 30" autofocus onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 1){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" id="descripcion" maxlength="100" required title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 100" onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 2){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp">
						<label for="tipo_insumo">Tipo Insumo</label>
						<select name="tipo_insumo" id="tipo_insumo">
					<?php
						$query_ti = mysqli_query($conection, "SELECT * FROM tipoinsumo WHERE 1=1 ORDER BY id_tipo_insumo ASC");
						$result_num = mysqli_num_rows($query_ti);
						if ($result_num > 0) 
						{
							while($result_ti = mysqli_fetch_array($query_ti))
							{
								$value = (int)$result_ti['id_tipo_insumo'];
								$option = htmlspecialchars($result_ti['descripcion_tipo_insumo']);
								$select .= "<option value=\"$value\">$option</option>";
							}					
							echo $select;
						}
						else
						{
							echo "<option value=\"0\">No hay registros aún</option>";
						}
					?>
						</select>
					</div>
					<div class="divisor_resp">
						<label for="unidad">Unidad</label>
						<select name="unidad" id="unidad">
					<?php
						$query_u = mysqli_query($conection, "SELECT * FROM unidades WHERE 1=1 ORDER BY id_unidad ASC");
						$result_unum = mysqli_num_rows($query_u);
						if ($result_num > 0) 
						{
							while($result_u = mysqli_fetch_array($query_u))
							{
								$valueu = (int)$result_u['id_unidad'];
								$optionu = htmlspecialchars($result_u['descripcion']);
								$selectu .= "<option value=\"$valueu\">$optionu</option>";
							}					
							echo $selectu;
						}
						else
						{
							echo "<option value=\"0\">No hay registros aún</option>";
						}
					?>
						</select>
					</div>
					<div class="divisor_resp">
						<label for="costo">Costo</label>
						<input type="text" name="costo" id="costo" required title="Introduzca el precio de la herramientas" <?php if(isset($code) && $code == 3){ echo "autofocus"; } ?> />
					</div>					
					<div class="divisor_resp"></div>
					<button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear herramientas</button>
				</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>