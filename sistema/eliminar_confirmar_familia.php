<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }
  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_familia'])){
        header("location: familias.php");  
    }

    $id_familia = (int)$_POST['id_familia'];

    $query_delete = mysqli_query($conection,"UPDATE familias SET estatus=0 WHERE id_familia='$id_familia'");  

  	if ($query_delete) {
  		//echo "Familia Eliminada Correctamente";
        header("location: familias.php");
  	}else{
  		echo "Error al eliminar familia";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: familias.php");
}else{
	
	$id_familia = (int)$_REQUEST['id'];

	$query = mysqli_query($conection, "SELECT * FROM familias WHERE id_familia='$id_familia'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$familia = $data['familia'];
			$descripcion = $data['descripcion'];
		}
	}else{
			header("location: familias.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar Familia</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="fas fa-eraser fa-7x" style="color:#e66262"></i>
		<br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Familia: <span><?php echo $familia; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_familia" value="<?php echo $id_familia; ?>">
	
				<a href="familias.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>