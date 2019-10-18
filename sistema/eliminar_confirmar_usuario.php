<?php
require_once "includes/verifica_sesion.php";

    if($_SESSION['id_rol'] != 1){
            header("location: ./");
    }

  include "conexion.php";

  if (!empty($_POST)) {
  	
    if($_POST['idusuario'] == 1){
        header("location: lista_usuarios.php");
        exit;
    }
      $idusuario = $_POST['idusuario'];

  	//$query_delate = mysqli_query($conection,"DELETE FROM usuarios Where id_usuario = $idusuario; ");
    $query_delate = mysqli_query($conection,"UPDATE usuarios SET estatus = 0 WHERE id_usuario = $idusuario");  

  	if ($query_delate) {
  		header("location: lista_usuarios.php");
  	}else{
  		echo "Error al eliminar";
  	}
  }


if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
	header("location: lista_usuarios.php");
}else{
	
	$idusuario = $_REQUEST['id'];

	$query = mysqli_query($conection, "SELECT u.nombre_usuario, u.apellido_pater_usuario, u.apellido_mater_usuario, u.login_usuario, r.descripcion_rol FROM usuarios u INNER JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = $idusuario");
    mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if ($result > 0) {
		while ($data = mysqli_fetch_array($query)) {
			$nombre = $data['nombre_usuario'];
			$apaterno = $data['apellido_pater_usuario'];
			$amaterno = $data['apellido_mater_usuario'];
			$login = $data['login_usuario'];
			$rol = $data['descripcion_rol'];
		}
	}else{
			header("location: lista_usuarios.php");
	}
}
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; 	?>
	<title>Eliminar Usuarios</title>
</head>
<body>

	<?php  include "includes/header.php"; ?>
	<section id="container">
		<br>
		<div class="data_delete">
		    <i class="fas fa-user-times fa-7x" style="color:#e66262"></i>
		    <br>
		    <br>
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p></p>
			<p>Nombre: <span><?php echo $nombre." ".$apaterno." ".$amaterno; ?></span></p>
			<p>Login: <span><?php echo $login; ?></span></p>
			<p>Tipo Usuario: <span><?php echo $rol; ?></span></p>

			<form method="post" action="">
			<p></p>
				<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
	
				<a href="lista_usuarios.php" class="btn_cancel"><i class="fas fa-ban fa-lg"></i>  Cancelar</a>
				<button type="submit" class="btn_ok"><i class="fas fa-trash-alt fa-lg"></i> Eliminar</button>
				
				
				
			</form>
		</div>
		
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>