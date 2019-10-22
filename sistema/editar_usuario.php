<?php

require_once "includes/verifica_sesion.php";
if ($_SESSION['id_rol'] != 1) {
    header("location: ./");
}
include "conexion.php";
include "includes/encriptacion.php";

if (isset($_POST['btn-signup'])) {
    $alert = '';
    $idUsuario = $_POST['id'];
    $nombre = strtoupper($_POST['nombre']);
    $apaterno = strtoupper($_POST['apaterno']);
    $amaterno = strtoupper($_POST['amaterno']);
    $sexo = $_POST['sexo'];
    $cargo = $_POST['cargo'];
    $telefono = $_POST['telefono'];
    $email = $_POST['correo'];
    $login_user = $_POST['usuario'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    if (empty($nombre)) {
        $alert = "Introduzca su nombre";
        $code = 1;
    } else if (!preg_match("/^[a-z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $nombre)) {
        $alert = "Su nombre debe contener solo letras";
        $code = 1;
    } else if (empty($apaterno)) {
        $alert = "Introduzca su apellido paterno";
        $code = 2;
    } else if (!preg_match("/^[a-z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $apaterno)) {
        $alert = "Sus apellidos deben contener solo letras";
        $code = 2;
    } else if (empty($amaterno)) {
        $alert = "Introduzca su apellido materno!";
        $code = 3;
    } else if (!preg_match("/^[a-z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $amaterno)) {
        $alert = "Sus apellidos deben contener solo letras";
        $code = 3;
    } else if (empty($telefono)) {
        $alert = "Introduzca su número de teléfono";
        $code = 4;
    } else if (!is_numeric($telefono)) {
        $alert = "Su teléfono debe contener solo números";
        $code = 4;
    } else if (strlen($telefono) != 10) {
        $alert = "Deben ser 10 números";
        $code = 4;
    } else if (empty($email)) {
        $alert = "Introduzca su correo electrónico";
        $code = 5;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "Introduzca una direción de correo electrónico válida";
        $code = 5;
    } /*else if (!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i", $email)) {
        $alert = "Dirección de Email no válida";
        $code = 5;
    } */ else if (empty($login_user)) {
        $alert = "Introduzca su Login";
        $code = 6;
    } else if (strlen($login_user) < 5) {
        $alert = "Mínimo 5 caracteres en su Login";
        $code = 6;
    } else if (strlen($login_user) > 10) {
        $alert = "Máximo 10 caracteres en su Login";
        $code = 6;
    } else if (empty($clave)) {
        $alert = "Introduzca su contraseña";
        $code = 7;
    } else if (strlen($clave) < 5) {
        $alert = "Mínimo 5 caracteres en la contraseña";
        $code = 7;
    } else if (strlen($clave) > 12) {
        $alert = "Máximo 12 caracteres en la contraseña";
        $code = 7;
    } else {
        $clave_encriptada = encriptacion::encryption($_POST['clave']);
        $login_user = mysqli_real_escape_string($conection, $login_user);
        $idUsuario = mysqli_real_escape_string($conection, $idUsuario);
        $email = mysqli_real_escape_string($conection, $email);
        $nombre = mysqli_real_escape_string($conection, $nombre);
        $apaterno = mysqli_real_escape_string($conection, $apaterno);
        $amaterno = mysqli_real_escape_string($conection, $amaterno);
        $sexo = mysqli_real_escape_string($conection, $sexo);
        $cargo = mysqli_real_escape_string($conection, $cargo);
        $telefono = mysqli_real_escape_string($conection, $telefono);
        $clave_encriptada = mysqli_real_escape_string($conection, $clave_encriptada);
        $rol = mysqli_real_escape_string($conection, $rol);
        $query = mysqli_query($conection, "SELECT * FROM usuarios WHERE (login_usuario = '$login_user' AND id_usuario != $idUsuario) OR (email = '$email' AND id_usuario != $idUsuario) ");
        $result = mysqli_fetch_array($query);
        //$result = count($result);

        if ($result > 0) {
            $alert = '<p class="msg_error"> El correo o el login introducidos ya se encuentran registrados</div>';
            $code = 8;
        } else {
            if (empty($_POST['clave'])) {
                $sql_update = mysqli_query($conection, "UPDATE usuarios SET nombre_usuario = '$nombre', apellido_pater_usuario = '$apaterno', apellido_mater_usuario = '$amaterno', id_sexo = $sexo, id_cargo = $cargo, telefono_celular = '$telefono', email = '$email', login_usuario = '$login_user', id_rol = $rol WHERE id_usuario = $idUsuario ");
            } else {
                $sql_update = mysqli_query($conection, "UPDATE usuarios SET nombre_usuario = '$nombre', apellido_pater_usuario = '$apaterno', apellido_mater_usuario = '$amaterno', id_sexo = $sexo, id_cargo = $cargo, telefono_celular = '$telefono', email = '$email', login_usuario = '$login_user', contrasena_usuario = '$clave_encriptada', id_rol = $rol WHERE id_usuario = $idUsuario");
            }
            if ($sql_update) {
                $alert = '<p class="msg_save"> Usuario actualizado correctamente.</div>';
                $code = 9;
            } else {
                $alert = '<p class="msg_error"> Error al actualizar el usuario.</div>';
                $code = 10;
            }
        }
    }
}

/* mostrar datos */
if (empty($_REQUEST['id'])) {
    header('Location: lista_usuarios.php');
    mysqli_close($conection);
}

$iduser = (int)$_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT u.id_usuario,u.nombre_usuario,u.apellido_pater_usuario,u.apellido_mater_usuario, u.id_sexo, 
  	                              s.descripcion_sexo, u.id_cargo, c.descripcion_cargo, u.telefono_celular, u.email, u.login_usuario, u.contrasena_usuario, u.id_rol, r.descripcion_rol 
								  FROM usuarios u 
								  INNER JOIN sexo s 
								  ON s.id_sexo = u.id_sexo 
								  INNER JOIN cargos c 
								  ON c.id_cargo = u.id_cargo 
								  INNER JOIN roles r 
								  ON r.id_rol = u.id_rol 
								  WHERE u.id_usuario = $iduser");

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: lista_usuarios.php');
} else {
    while ($data = mysqli_fetch_array($sql)) {
        $iduser = $data['id_usuario'];
        $nombre = $data['nombre_usuario'];
        $apaterno = $data['apellido_pater_usuario'];
        $amaterno = $data['apellido_mater_usuario'];
        $id_sexo = $data['id_sexo'];
        $sexo = $data['descripcion_sexo'];
        $id_cargo = $data['id_cargo'];
        $cargo = $data['descripcion_cargo'];
        $telefono = $data['telefono_celular'];
        $email = $data['email'];
        $usuario = $data['login_usuario'];
        $contrasena = encriptacion::decryption($data['contrasena_usuario']);
        $id_rol = $data['id_rol'];
        $descripcion_rol = $data['descripcion_rol'];

        /*  Para el Item de Sexo*/
        $option_sexo = '';
        $query_sexo_by_id = mysqli_query($conection, "SELECT * FROM sexo WHERE id_sexo = '$id_sexo'");
        $result_sexo_by_id = mysqli_fetch_array($query_sexo_by_id);
        $option_sexo = '<option value="' . $id_sexo . '" select>' . $result_sexo_by_id['descripcion_sexo'] . '</option>';

        /*  Para el Item del Cargo*/
        $option_cargo = '';
        $query_cargo_by_id = mysqli_query($conection, "SELECT * FROM cargos WHERE id_cargo = '$id_cargo'");
        $result_cargo_by_id = mysqli_fetch_array($query_cargo_by_id);
        $option_cargo = '<option value="' . $id_cargo . '" select>' . $result_cargo_by_id['descripcion_cargo'] . '</option>';

        /*  Para el Item del Rol*/
        $option_rol = '';
        $query_rol_by_id = mysqli_query($conection, "SELECT * FROM roles WHERE id_rol = '$id_rol'");
        $result_rol_by_id = mysqli_fetch_array($query_rol_by_id);
        $option_rol = '<option value="' . $id_rol . '" select>' . $result_rol_by_id['descripcion_rol'] . '</option>';
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Actualizar Usuario</title>
</head>
<body>
<?php include "includes/header.php"; ?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-user-edit fa-lg"></i> Actualizar usuario</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $iduser; ?>">

            <div class="divisor_resp">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" maxlength="70" required
                       pattern="[A-Za-z ñÑ À-ú]{2,50}" title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 50"
                       autofocus onchange="javascript:this.value=this.value.toUpperCase();">
            </div>

            <div class="divisor_resp">
                <label for="apellido_paterno">Apellido Paterno </label>
                <input type="text" name="apaterno" id="apaterno" value="<?php echo $apaterno; ?>" maxlength="40"
                       required pattern="[A-Za-z ñÑ À-ú]{2,40}"
                       title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 40"
                       onchange="javascript:this.value=this.value.toUpperCase();">
            </div>

            <div class="divisor_resp">
                <label for="apellido_materno">Apellido Materno </label>
                <input type="text" name="amaterno" id="amaterno" value="<?php echo $amaterno; ?>" maxlength="40"
                       required pattern="[A-Za-z ñÑ À-ú]{2,40}"
                       title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 40"
                       onchange="javascript:this.value=this.value.toUpperCase();">
            </div>

            <div class="divisor_resp">
                <label for="sexo">Sexo</label>
                <?php
                $query_sexo = mysqli_query($conection, "SELECT * FROM sexo");
                $result_sexo = mysqli_num_rows($query_sexo);
                ?>
                <select name="sexo" id="sexo" class="noMostrarPrimerItem">
                    <?php
                    echo $option_sexo;
                    if ($result_sexo > 0) {
                        while ($sexo = mysqli_fetch_array($query_sexo)) {
                            ?>
                            <option value="<?php echo $sexo["id_sexo"]; ?>"><?php echo $sexo["descripcion_sexo"] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="cargo"> Cargo del usuario</label>
                <?php
                $query_cargo = mysqli_query($conection, "SELECT * FROM cargos");
                $result_cargo = mysqli_num_rows($query_cargo);
                ?>
                <select name="cargo" id="cargo" class="noMostrarPrimerItem">
                    <?php
                    echo $option_cargo;
                    if ($result_cargo > 0) {
                        while ($cargo = mysqli_fetch_array($query_cargo)) {
                            ?>
                            <option value="<?php echo $cargo["id_cargo"]; ?>"><?php echo $cargo["descripcion_cargo"] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="celular">Número de Celular </label>
                <input type="tel" name="telefono" id="telefono" value="<?php echo $telefono; ?>" maxlength="10" required
                       pattern="[0-9]{10,10}" title="Introduzca sólo números. Tamaño mínimo: 10. Tamaño máximo: 10">
            </div>

            <div class="divisor_resp">
                <label for="correo"> Correo electrónico</label>
                <input type="text" name="correo" id="correo" value="<?php echo $email; ?>" required
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       title="Ejemplo: dina@gmail.com">
            </div>

            <div class="divisor_resp">
                <label for="usuario">Login</label>
                <input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" maxlength="10" required
                       pattern="[A-Za-z0-9]{5,10}" title="Letras y números. Tamaño mínimo: 5. Tamaño máximo: 10">
            </div>

            <div class="divisor_resp">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" value="<?php echo $contrasena; ?>" maxlength="12"
                       pattern="[A-Za-z0-9]{5,10}" title="Letras y números. Tamaño mínimo: 5. Tamaño máximo: 12">
            </div>

            <div class="divisor_resp">
                <label for="rol">Rol</label>
                <?php
                $query_rol = mysqli_query($conection, "SELECT * FROM roles");
                mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);
                ?>
                <select name="rol" id="rol" class="noMostrarPrimerItem">
                    <?php
                    echo $option_rol;
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                            ?>
                            <option value="<?php echo $rol["id_rol"]; ?>"><?php echo $rol["descripcion_rol"] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp"></div>
            <button type="submit" class="btn_save" name="btn-signup"><i class="fas fa-user-edit"></i> Actualizar Usuario
            </button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
</body>
</html>