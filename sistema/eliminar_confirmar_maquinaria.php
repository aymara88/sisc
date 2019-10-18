<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }
  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_maquinaria'])){
        header("location: maquinaria.php");  
    }

    $id_maquinaria = mysqli_real_escape_string($conection, $_POST['id_maquinaria']);
    $query_delete = mysqli_query($conection,"UPDATE maquinaria SET estatus=0 WHERE codigo_maquinaria='$id_maquinaria'");  

  	if ($query_delete) {
  		echo "Maquinaria eliminada correctamente";
  	}else{
  		echo "Error al eliminar maquinaria";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: maquinaria.php");
}else{
	
	$id_maquinaria = mysqli_real_escape_string($conection, $_REQUEST['id']);
	$query = mysqli_query($conection, "SELECT * FROM maquinaria WHERE codigo_maquinaria='$id_maquinaria'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$maquinaria = $data['codigo_maquinaria'];
			$descripcion = $data['descripcion_maquinaria'];
		}
	}else{
			header("location: maquinaria.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar maquinaria</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="fal fa-trash-alt fa-7x" style="color:#e66262"></i>
		    <br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Código: <span><?php echo $maquinaria; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_maquinaria" value="<?php echo $id_maquinaria; ?>">
	
				<a href="maquinaria.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>