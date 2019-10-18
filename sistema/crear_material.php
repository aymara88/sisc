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
        $material = strtoupper($_POST['codigo']);
		$descripcion = strtoupper($_POST['descripcion']);
		$tipo_insumo = $_POST['tipo_insumo'];
		$unidad = (int)$_POST['unidad'];
		$familia = (int)$_POST['familia'];
		$proveedor = (int)$_POST['proveedor'];
		$costo = (float)$_POST['costo'];
		
        if(empty($material)){
            $alert = "Introduce el código del material!";
            $code = 1;
        }
        else if(empty($descripcion)){
            $alert = "Necesitas agregar una descripción del material";
            $code = 2;
        }else if(!is_numeric($costo)){
            $alert = "El costo es incorrecto!";
            $code = 3;
        }else if(!is_numeric($tipo_insumo)){
            $alert = "Tipo de insumo incorrecto!";
            $code = 4;
        }else if(!is_numeric($unidad)){
            $alert = "La unidad es incorrecta!";
            $code = 5;
        }else if(!is_numeric($familia)){
            $alert = "La familia es incorrecta!";
            $code = 6;
        }else if(!is_numeric($proveedor)){
            $alert = "El proveedor es incorrecto!";
            $code = 7;
        }else{
			$material = mysqli_real_escape_string($conection, $material);
			$descripcion = mysqli_real_escape_string($conection, $descripcion);
			$tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
			$unidad = mysqli_real_escape_string($conection, $unidad);
			$familia = mysqli_real_escape_string($conection, $familia);
			$proveedor = mysqli_real_escape_string($conection, $proveedor);
			$costo = mysqli_real_escape_string($conection, $costo);
            $query = mysqli_query($conection, "SELECT * FROM materiales WHERE codigo_material='$material'");
            $result = mysqli_fetch_array($query);
			if ($result > 0) {
                $alert = "El material que intentas crear ya existe!";
                $code = 8;
			}else{
                $query_insert = mysqli_query($conection,"INSERT INTO materiales(codigo_material, descripcion_material, id_tipo_insumo, id_unidad, id_familia, id_proveedor, costo_material) VALUES('$material','$descripcion','$tipo_insumo', '$unidad', '$familia', '$proveedor', '$costo')");
				
                if ($query_insert){                    
                    $alert="Material creado correctamente!";
                    $code = 9;
                   
                }else{
                    $alert = "Error al crear material!";
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
	<title>Nuevo material</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	<section id="container">
		<div class="form_register">
			<br>
			<h1><i class="fas fa-paint-roller fa-lg"></i> Alta de materiales</h1>
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
						<input type="text" name="codigo" id="codigo" maxlength="30" required title="Tamaño mínimo: 2. Tamaño máximo: 30" autofocus onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 1){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" id="descripcion" maxlength="100" required title="Tamaño mínimo: 2. Tamaño máximo: 100" onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 2){ echo "autofocus"; } ?> />
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
						<label for="familia">Familia</label>
						<select name="familia" id="familia">
					<?php
						$query_f = mysqli_query($conection, "SELECT * FROM familias WHERE 1=1 ORDER BY id_familia ASC");
						$result_fnum = mysqli_num_rows($query_f);
						if ($result_fnum > 0) 
						{
							while($result_f = mysqli_fetch_array($query_f))
							{
								$valuef = (int)$result_f['id_familia'];
								$optionf = htmlspecialchars($result_f['familia']);
								$selectf .= "<option value=\"$valuef\">$optionf</option>";
							}
							echo $selectf;
						}
						else
						{
							echo "<option value=\"0\">No hay registros aún</option>";
						}
					?>
						</select>
					</div>
					<div class="divisor_resp">
						<label for="proveedor">Proveedor</label>
						<select name="proveedor" id="proveedor">
					<?php
						$query_p = mysqli_query($conection, "SELECT id_proveedor, razon_social FROM proveedores WHERE 1=1 ORDER BY id_proveedor ASC");
						$result_pnum = mysqli_num_rows($query_p);
						if ($result_pnum > 0) 
						{
							while($result_p = mysqli_fetch_array($query_p))
							{
								$valuep = (int)$result_p['id_proveedor'];
								$optionp = htmlspecialchars($result_p['razon_social']);
								$selectp .= "<option value=\"$valuep\">$optionp</option>";
							}
							echo $selectp;
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
						<input type="text" name="costo" id="costo" required title="Introduzca el precio del producto" <?php if(isset($code) && $code == 3){ echo "autofocus"; } ?> />
					</div>					
					<div class="divisor_resp"></div>
					<button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear material</button>
				</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>