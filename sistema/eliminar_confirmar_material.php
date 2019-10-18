<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }
	
  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_material'])){
        header("location: materiales.php");  
    }

    $id_material = mysqli_real_escape_string($conection, $_POST['id_material']);
    $query_delete = mysqli_query($conection,"UPDATE materiales SET estatus=0 WHERE codigo_material='$id_material'");  

  	if ($query_delete) {
  		echo "Material eliminado correctamente";
  	}else{
  		echo "Error al eliminar material";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: materiales.php");
}else{
	
	$id_material = mysqli_real_escape_string($conection, $_REQUEST['id']);
	$query = mysqli_query($conection, "SELECT * FROM materiales WHERE codigo_material='$id_material'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$material = $data['codigo_material'];
			$descripcion = $data['descripcion_material'];
		}
	}else{
			header("location: materiales.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar material</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="fas fa-backspace fa-7x" style="color:#e66262"></i>
		<br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Código: <span><?php echo $material; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_material" value="<?php echo $id_material; ?>">
	
				<a href="materiales.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>