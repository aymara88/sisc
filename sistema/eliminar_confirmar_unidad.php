<?php
require_once "includes/verifica_sesion.php";

  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_unidad'])){
        header("location: unidades.php");  
    }

    $id_unidad = (int)$_POST['id_unidad'];

    $query_delete = mysqli_query($conection,"UPDATE unidades SET estatus=0 WHERE id_unidad='$id_unidad'");  

  	if ($query_delete) {
  		//echo "Unidad eliminada correctamente";
        header("location: unidades.php");
    }else{
  		echo "Error al eliminar unidad";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: unidades.php");
}else{
	
	$id_unidad = (int)$_REQUEST['id'];

	$query = mysqli_query($conection, "SELECT * FROM unidades WHERE id_unidad='$id_unidad'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$unidad = $data['abreviatura_unidad'];
			$descripcion = $data['descripcion'];
		}
	}else{
			header("location: unidades.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar Unidad</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="far fa-eraser fa-7x" style="color:#e66262"></i>
		<br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Abreviatura Unidad: <span><?php echo $unidad; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_unidad" value="<?php echo $id_unidad; ?>">
	
				<a href="unidades.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>