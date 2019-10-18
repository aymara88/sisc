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
        $familia = strtoupper($_POST['familia']);
		$descripcion = strtoupper($_POST['descripcion']);
		
        if(empty($familia)){
            $alert = "Introduce el nombre de la nueva familia!";
            $code = 1;
        }else if(empty($descripcion)){
            $alert = "Debe llevar una descripción";
            $code = 2;
        }
		else{
			$familia = mysqli_real_escape_string($conection, $familia);
			$descripcion = mysqli_real_escape_string($conection, $descripcion);		
            $query = mysqli_query($conection, "SELECT * FROM familias WHERE familia='$familia'");
            $result = mysqli_fetch_array($query);
			if ($result > 0) {
                $alert = "La familia que intentas crear ya existe!";
                $code = 3;
			}else{
                $query_insert = mysqli_query($conection,"INSERT INTO familias(familia, descripcion) VALUES('$familia','$descripcion')");
				
                if ($query_insert){
                     
                    $alert="Familia creada correctamente!";
                    $code = 4;
                   
                }else{
                    $alert = "Error al crear familia!";
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
	<title>Nueva Familia</title>
</head>
<body>

	<?php  
        include "includes/header.php"; 
    ?>
	<section id="container">
		<div class="form_register">
			<br>
			<h1><i class="fas fa-toolbox fa-lg"></i> Crear una nueva familia</h1>
			<hr>
			<?php if(isset($alert)){ ?>
			<div class="alert"><?php echo $alert; ?>
			</div>
			<?php 
                } 
            ?>
				<form action="" method="post" name="miForm">
					<div class="divisor_resp">
						<label for="familia">Familia</label>
						<input type="text" name="familia" id="familia" maxlength="100" required title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 100" autofocus onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 1){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" id="descripcion" maxlength="100" required title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 100" onchange="javascript:this.value=this.value.toUpperCase();" <?php if(isset($code) && $code == 2){ echo "autofocus"; } ?> />
					</div>
					<div class="divisor_resp"></div>
					<button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear Familia</button>
				</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>