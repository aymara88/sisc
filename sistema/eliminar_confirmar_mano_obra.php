<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }
  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_mano_obra'])){
        header("location: mano_obra.php");  
    }

    $id_mano_obra = mysqli_real_escape_string($conection, $_POST['id_mano_obra']);
    $query_delete = mysqli_query($conection,"UPDATE mano_obra SET estatus=0 WHERE codigo_mano_obra='$id_mano_obra'");  

  	if ($query_delete) {
  		//echo "Mano de obra eliminada correctamente";
        header("location: mano_obra.php");
    }else{
  		echo "Error al eliminar mano de obra";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: mano_obra.php");
}else{
	
	$id_mano_obra = mysqli_real_escape_string($conection, $_REQUEST['id']);
	$query = mysqli_query($conection, "SELECT * FROM mano_obra WHERE codigo_mano_obra='$id_mano_obra'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$mano_obra = $data['codigo_mano_obra'];
			$descripcion = $data['descripcion_mano_obra'];
		}
	}else{
			header("location: mano_obra.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar mano de obra</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Código: <span><?php echo $mano_obra; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_mano_obra" value="<?php echo $id_mano_obra; ?>">
	
				<a href="mano_obra.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>