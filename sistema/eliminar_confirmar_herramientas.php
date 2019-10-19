<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2){
        header("location: ./");
    }
  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['id_herramientas'])){
        header("location: herramientas.php");  
    }

    $id_herramientas = mysqli_real_escape_string($conection, $_POST['id_herramientas']);
    $query_delete = mysqli_query($conection,"UPDATE herramientas SET estatus=0 WHERE codigo_herramienta='$id_herramientas'");  

  	if ($query_delete) {
  		//echo "Herramienta eliminada correctamente";
        header("location: herramientas.php");
    }else{
  		echo "Error al eliminar herramientas";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: herramientas.php");
}else{
	
	$id_herramientas = mysqli_real_escape_string($conection, $_REQUEST['id']);
	$query = mysqli_query($conection, "SELECT * FROM herramientas WHERE codigo_herramienta='$id_herramientas'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$herramientas = $data['codigo_herramienta'];
			$descripcion = $data['descripcion_herramienta'];
		}
	}else{
			header("location: herramientas.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar herramientas</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="fas fa-trash-alt fa-7x" style="color:#e66262"></i>
		    <br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Código: <span><?php echo $herramientas; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="id_herramientas" value="<?php echo $id_herramientas; ?>">
	
				<a href="herramientas.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>