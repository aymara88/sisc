<?php
require_once "includes/verifica_sesion.php";

  include "conexion.php";

  if (!empty($_POST)) {
    if(empty($_POST['idcliente'])){
        header("location: lista_clientes.php");  
    }

    $idcliente = $_POST['idcliente'];

    $query_delate = mysqli_query($conection,"UPDATE clientes SET estatus = 0 WHERE id_cliente = $idcliente");  

  	if ($query_delate) {
  		header("location: lista_clientes.php");
  	}else{
  		echo "Error al eliminar el cliente";
  	}
  }


if (empty($_REQUEST['id'])) {
	header("location: lista_clientes.php");
}else{
	
	$idcliente = $_REQUEST['id'];

	$query = mysqli_query($conection, "SELECT * FROM clientes WHERE id_cliente = $idcliente");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$nombre_cliente = $data['nombre_cliente'];
			$rfc_cliente = $data['rfc_cliente'];
		}
	}else{
			header("location: lista_clientes.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar Ciente</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
			<i class="fas fa-ban fa-7x" style="color:#e66262"></i>
			<br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Nombre: <span><?php echo $nombre_cliente; ?></span></p>
			<p>RFC: <span><?php echo $rfc_cliente; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">
	
				<a href="lista_clientes.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>