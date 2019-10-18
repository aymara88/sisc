<?php 
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1){
        header("location: ./");
    }

	include "conexion.php";
	include "frontend/encriptacion.php";
    require_once('frontend/CrudUsuario.php');

    if(isset($_POST['btn-signup'])){
        $alert='';
        $unidad = strtoupper($_POST['unidad']);
		$descripcion = strtoupper($_POST['descripcion']);
		
        if(empty($unidad)){
            $alert = "Introduce el nombre de la nueva unidad!";
            $code = 1;
        }
        if(empty($descripcion)){
            $alert = "Debe introducir una descripción válida";
            $code = 2;
        }else{
			$unidad = mysqli_real_escape_string($conection, $unidad);
			$descripcion = mysqli_real_escape_string($conection, $descripcion);
            $query = mysqli_query($conection, "SELECT * FROM unidades WHERE abreviatura_unidad='$unidad'");
            $result = mysqli_fetch_array($query);
			if ($result > 0) {
                $alert = "La unidad que intentas crear ya existe!";
                $code = 3;
			}else{
                $query_insert = mysqli_query($conection,"INSERT INTO unidades(abreviatura_unidad, descripcion) VALUES('$unidad','$descripcion')");
				
                if ($query_insert){                    
                    $alert="Unidad creada correctamente!";
                    $code = 4;
                   
                }else{
                    $alert = "Error al crear unidad!";
                    $code = 5;
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
	<title>Nueva Unidad</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	<section id="container">
		<div class="form_register">
			<br>
			<h1><i class="fas fa-ruler fa-lg"></i> Crear una nueva unidad</h1>
			<hr>
			<?php if(isset($alert)){ ?>
			<div class="alert"><?php echo $alert; ?>
			</div>
			<?php 
                } 
            ?>
				<form action="" method="post" name="miForm">
					<div class="divisor_resp">
						<label for="unidad">Abreviatura Unidad</label>
						<input type="text" name="unidad" id="unidad" maxlength="25" required title="Tamaño mínimo: 2. Tamaño máximo: 100" autofocus onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 1){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" id="descripcion" maxlength="100" required title="Tamaño mínimo: 2. Tamaño máximo: 100" onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 2){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp"></div>
					<button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear unidad</button>
				</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>