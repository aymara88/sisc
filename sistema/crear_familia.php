<?php
require_once "includes/verifica_sesion.php";

if ($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2) {
    header("location: ./");
}

include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');

if (isset($_POST['btn-signup'])) {
    $alert = '';
    $familia = strtoupper($_POST['familia']);
    $descripcion = strtoupper($_POST['descripcion']);

    if (empty($familia)) {
        $alert = "Introduce el nombre de la nueva familia!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Debe llevar una descripción";
        $code = 2;
    } else {
        $familia = mysqli_real_escape_string($conection, $familia);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $query = mysqli_query($conection, "SELECT * FROM familias WHERE familia='$familia'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = "La familia que intentas crear ya existe!";
            $code = 3;
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO familias(familia, descripcion) VALUES('$familia','$descripcion')");

            if ($query_insert) {

                $alert = "Familia creada correctamente!";
                $code = 4;

                //vaciar formulario
                $familia = "";
                $descripcion = "";

            } else {
                $alert = "Error al crear familia!";
                $code = 5;

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
    <title>Nueva Familia</title>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-toolbox fa-lg"></i> Crear una nueva familia</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post" name="miForm">
            <div class="divisor_resp">
                <label for="familia">Familia</label>
                <input type="text" name="familia" id="familia" maxlength="50" required pattern="[A-Za-z ñÑ À-ú]{5,50}"
                       title="Introduzca sólo letras. Tamaño mínimo: 5. Tamaño máximo: 50"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> value="<?php if (isset($familia) && isset($code) && $code !== 1 && $code !== 3) {
                    echo $familia;
                } else {
                    echo '';
                } ?>"/>
            </div>
            <div class="divisor_resp">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" maxlength="80" required
                       pattern="[A-Za-z ñÑ À-ú]{5,80}"
                       title="Introduzca sólo letras. Tamaño mínimo: 5. Tamaño máximo: 80"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> value="<?php if (isset($descripcion) && isset($code) && $code !== 2) {
                    echo $descripcion;
                } else {
                    echo '';
                } ?>"/>
            </div>
            <div class="divisor_resp"></div>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear Familia
            </button>
        </form>
    </div>

</section>

<?php
if (isset($code) && $code == 4) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Familias</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Familia</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>

            <?php

            include "conexion.php";
            $por_pagina = 10;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $query = mysqli_query($conection, "SELECT * FROM familias
													WHERE estatus=1
													ORDER BY id_familia DESC LIMIT $desde,$por_pagina
									");
            //mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?php echo $data["id_familia"]; ?></td>
                        <td><?php echo $data["familia"]; ?></td>
                        <td><?php echo $data["descripcion"]; ?></td>
                        <td>
                            <a class="link_edit" href="editar_familia.php?id=<?php echo (int)$data["id_familia"]; ?>"><i
                                        class="fas fa-user-edit"></i> Editar</a>
                            |
                            <a class="link_eliminar"
                               href="eliminar_confirmar_familia.php?id=<?php echo (int)$data["id_familia"]; ?>"><i
                                        class="fas fa-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <div class="paginador">
            <ul>
                <li><a href="familias.php" title="Volver al Listado de Familias"><i
                                class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>

<?php include "includes/footer.php"; ?>
</body>
</html>