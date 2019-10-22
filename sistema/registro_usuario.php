<?php
require_once "includes/verifica_sesion.php";
if ($_SESSION['id_rol'] != 1) {
    header("location: ./");
}
include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');

if (isset($_POST['btn-signup'])) {
    $alert = '';
    $nombre = strtoupper($_POST['nombre']);
    $apaterno = strtoupper($_POST['apaterno']);
    $amaterno = strtoupper($_POST['amaterno']);
    $sexo = $_POST['sexo'];
    $cargo = $_POST['cargo'];
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['correo']);
    $login_user = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);
    $rol = $_POST['rol'];

    /*  Para el Item de Sexo*/
    $option_sexo = '';
    $query_sexo_by_id = mysqli_query($conection, "SELECT * FROM sexo WHERE id_sexo = '$sexo'");
    $result_sexo_by_id = mysqli_fetch_array($query_sexo_by_id);
    $option_sexo = '<option value="' . $sexo . '" select>' . $result_sexo_by_id['descripcion_sexo'] . '</option>';

    /*  Para el Item del Cargo*/
    $option_cargo = '';
    $query_cargo_by_id = mysqli_query($conection, "SELECT * FROM cargos WHERE id_cargo = '$cargo'");
    $result_cargo_by_id = mysqli_fetch_array($query_cargo_by_id);
    $option_cargo = '<option value="' . $cargo . '" select>' . $result_cargo_by_id['descripcion_cargo'] . '</option>';

    /*  Para el Item del Rol*/
    $option_rol = '';
    $query_rol_by_id = mysqli_query($conection, "SELECT * FROM roles WHERE id_rol = '$rol'");
    $result_rol_by_id = mysqli_fetch_array($query_rol_by_id);
    $option_rol = '<option value="' . $rol . '" select>' . $result_rol_by_id['descripcion_rol'] . '</option>';

    if (empty($nombre)) {
        $alert = "Introduzca su nombre";
        $code = 1;
    } else if (!preg_match("/^[a-zA-Z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $nombre)) {
        $alert = "Su nombre debe contener solo letras";
        $code = 1;
    } else if (empty($apaterno)) {
        $alert = "Introduzca su apellido paterno";
        $code = 2;
    } else if (!preg_match("/^[a-zA-Z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $apaterno)) {
        $alert = "Sus apellidos deben contener solo letras";
        $code = 2;
    } else if (empty($amaterno)) {
        $alert = "Introduzca su apellido materno";
        $code = 3;
    } else if (!preg_match("/^[a-zA-Z áéíóúüñÑÁÉÍÓÚÜ]+$/i", $amaterno)) {
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
    }/* else if (!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i", $email)) {
        $alert = "Dirección de Email no válida";
        $code = 5;
    }*/ else if (empty($login_user)) {
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
        $login_user = mysqli_real_escape_string($conection, $login_user);
        $email = mysqli_real_escape_string($conection, $email);
        $nombre = mysqli_real_escape_string($conection, $nombre);
        $apaterno = mysqli_real_escape_string($conection, $apaterno);
        $amaterno = mysqli_real_escape_string($conection, $amaterno);
        $sexo = mysqli_real_escape_string($conection, $sexo);
        $cargo = mysqli_real_escape_string($conection, $cargo);
        $telefono = mysqli_real_escape_string($conection, $telefono);
        $rol = mysqli_real_escape_string($conection, $rol);

        $query = mysqli_query($conection, "SELECT * FROM usuarios WHERE login_usuario = '$login_user' OR email = '$email'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = "El correo o el login introducidos ya se encuentran registrados";
            $code = 8;
        } else {
            $clave = encriptacion::encryption($_POST['clave']);
            $clave = mysqli_real_escape_string($conection, $clave);
            $query_insert = mysqli_query($conection, "INSERT INTO usuarios(nombre_usuario,apellido_pater_usuario,apellido_mater_usuario,id_sexo,id_cargo,telefono_celular,email,login_usuario,contrasena_usuario,id_rol) VALUES('$nombre','$apaterno','$amaterno','$sexo','$cargo','$telefono','$email','$login_user','$clave','$rol') ");

            if ($query_insert) {
                $alert = "Usuario creado correctamente";
                $code = 9;
                $login_user = '';
                $email = '';
                $nombre = '';
                $apaterno = '';
                $amaterno = '';
                $sexo = '';
                $cargo = '';
                $telefono = '';
                $rol = '';
                $option_rol = '';
                $option_cargo = '';
                $option_sexo = '';
            } else {
                $alert = "Error al dar de alta al usuario";
                $code = 10;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de Usuarios</title>
</head>
<body>
<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <h1><i class="fas fa-user-friends fa-lg"></i> Registro de usuarios</h1>
        <hr>

        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>

        <form action="" method="post" name="miForm">

            <!-- Puedo agregar esta linea a los input por validacion de campos a solo texto y
                 que ademas no permita ni siquiera introducir por ejemplo numeros a los campos
                 de solo texto como nombre, apellidos etc
                 onkeypress="return /[a-z]/i.test(event.key)"-->

            <div class="divisor_resp">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" maxlength="50" required pattern="[A-Za-z ñÑ À-ú]{2,50}"
                       title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 50"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> value="<?php if (isset($nombre) && isset($code) && $code == 8) {
                    echo $nombre;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="apellidop">Apellido Paterno</label>
                <input type="text" name="apaterno" id="apaterno" maxlength="40" required pattern="[A-Za-z ñÑ À-ú]{2,40}"
                       title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 40"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> value="<?php if (isset($apaterno) && isset($code) && $code == 8) {
                    echo $apaterno;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="apellidom">Apellido Materno</label>
                <input type="text" name="amaterno" id="amaterno" maxlength="40" required pattern="[A-Za-z ñÑ À-ú]{2,40}"
                       title="Introduzca sólo letras. Tamaño mínimo: 2. Tamaño máximo: 40"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 3) {
                    echo "autofocus";
                } ?> value="<?php if (isset($amaterno) && isset($code) && $code == 8) {
                    echo $amaterno;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="sexo">Sexo</label>
                <?php
                $query_sexo = mysqli_query($conection, "SELECT * FROM sexo");
                $result_sexo = mysqli_num_rows($query_sexo);
                ?>

                <select name="sexo" id="sexo" class="<?php if (isset($option_sexo) && !empty($option_sexo)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
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
                <select name="cargo" id="cargo" class="<?php if (isset($option_cargo) && !empty($option_cargo)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
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
                <label for="telefono">Número de Teléfono</label>
                <input type="tel" name="telefono" id="telefono" maxlength="10" required pattern="[0-9]{10,10}"
                       title="Introduzca sólo números. Tamaño mínimo: 10. Tamaño máximo: 10" <?php if (isset($code) && $code == 4) {
                    echo "autofocus";
                } ?> value="<?php if (isset($telefono) && isset($code) && $code == 8) {
                    echo $telefono;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="correo">Correo Electronico</label>
                <input type="text" name="correo" id="correo" required
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       title="Ejemplo: dina@gmail.com" <?php if (isset($code) && $code == 5) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="login">Login</label>
                <input type="text" name="usuario" id="usuario" maxlength="10" required pattern="[A-Za-z0-9]{5,10}"
                       title="Letras y números. Tamaño mínimo: 5. Tamaño máximo: 10" <?php if (isset($code) && $code == 6) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" maxlength="12" required pattern="[A-Za-z0-9]{5,10}"
                       title="Letras y números. Tamaño mínimo: 5. Tamaño máximo: 12" <?php if (isset($code) && $code == 7) {
                    echo "autofocus";
                } ?> value="<?php if (isset($clave) && isset($code) && $code == 8) {
                    echo $clave;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="rol">Rol</label>
                <?php
                $query_rol = mysqli_query($conection, "SELECT * FROM roles");
                mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);
                ?>
                <select name="rol" id="rol" class="<?php if (isset($option_rol) && !empty($option_rol)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
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
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-user-plus"></i> Crear Usuario
            </button>

        </form>
    </div>
</section>

<?php
if (isset($code) && $code == 9) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Usuarios</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre(s)</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Sexo</th>
                <th>Cargo</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Login</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>

            <?php

            include "conexion.php";
            $por_pagina = 8;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;

            $query = mysqli_query($conection, "SELECT u.id_usuario,u.nombre_usuario,u.apellido_pater_usuario,u.apellido_mater_usuario, s.descripcion_sexo,c.descripcion_cargo, u.telefono_celular, u.email, u.login_usuario, r.descripcion_rol FROM usuarios u INNER JOIN sexo s ON s.id_sexo = u.id_sexo INNER JOIN cargos c ON c.id_cargo = u.id_cargo INNER JOIN roles r ON r.id_rol = u.id_rol WHERE u.estatus = 1 ORDER BY u.id_usuario DESC LIMIT $desde,$por_pagina");
            //mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?php echo $data["id_usuario"]; ?></td>
                        <td><?php echo $data["nombre_usuario"]; ?></td>
                        <td><?php echo $data["apellido_pater_usuario"]; ?></td>
                        <td><?php echo $data["apellido_mater_usuario"]; ?></td>
                        <td><?php echo $data["descripcion_sexo"]; ?></td>
                        <td><?php echo $data["descripcion_cargo"]; ?></td>
                        <td><?php echo $data["telefono_celular"]; ?></td>
                        <td><?php echo $data["email"]; ?></td>
                        <td><?php echo $data["login_usuario"]; ?></td>
                        <td><?php echo $data["descripcion_rol"]; ?></td>
                        <td>
                            <a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;"
                               href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i
                                        class="fas fa-user-edit"></i>&nbsp;Editar</a>
                            <?php if ($data["id_usuario"] != 1) { ?>
                                <a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;"
                                   href="eliminar_confirmar_usuario.php?id=<?php echo $data["id_usuario"]; ?>"><i
                                            class="fas fa-trash"></i>&nbsp;Eliminar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

        <div class="paginador">
            <ul>
                <li><a href="lista_usuarios.php" title="Volver al Listado de Usuarios"><i class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>
<?php include "includes/footer.php"; ?>
</body>
</html>