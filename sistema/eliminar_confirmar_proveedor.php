<?php
require_once "includes/verifica_sesion.php";

  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['idprov'])){
        header("location: lista_proveedores.php");  
    }

    $idprov = (int)$_POST['idprov'];

    $query_delete = mysqli_query($conection,"UPDATE proveedores SET estatus=0 WHERE id_proveedor = $idprov");  

  	if ($query_delete) {
  		header("location: lista_proveedores.php");
  	}else{
  		echo "Error al eliminar el proveedor";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: lista_proveedores.php");
}else{
	
	$idprov = (int)$_REQUEST['id'];

	$query = mysqli_query($conection, "SELECT * FROM proveedores WHERE id_proveedor='$idprov'");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$razon_social = $data['razon_social'];
			$rfc = $data['rfc'];
		}
	}else{
			header("location: lista_proveedores.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar Proveedor</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		<i class="fal fa-user-times fa-7x" style="color:#e66262"></i>
		    <br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Razón Social: <span><?php echo $razon_social; ?></span></p>
			<p>RFC: <span><?php echo $rfc; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="idprov" value="<?php echo $idprov; ?>">
	
				<a href="lista_proveedores.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>