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
    $unidad = strtoupper($_POST['unidad']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = strtoupper($_POST['tipo_insumo']);

    if (empty($unidad)) {
        $alert = "Introduce el nombre de la nueva unidad!";
        $code = 1;
    }
    if (empty($descripcion)) {
        $alert = "Debe introducir una descripción válida";
        $code = 2;
    } else {
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);

        $query = mysqli_query($conection, "SELECT * FROM unidades WHERE abreviatura_unidad='$unidad'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = "La unidad que intentas crear ya existe!";
            $code = 3;
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO unidades(abreviatura_unidad, descripcion, tipo_insumo) VALUES('$unidad','$descripcion', '$tipo_insumo')");

            if ($query_insert) {
                $alert = "Unidad creada correctamente!";
                $code = 4;

                //vaciar formulario
                $unidad = "";
                $descripcion = "";
                $tipo_insumo = "";

            } else {
                $alert = "Error al crear unidad!";
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
    <title>Nueva Unidad</title>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-ruler fa-lg"></i> Crear una nueva unidad</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post" name="miForm">
            <div class="divisor_resp">
                <label for="unidad">Abreviatura Unidad</label>
                <input type="text" name="unidad" id="unidad" maxlength="25" required pattern="[A-Za-z0-9 ñÑ À-ú % /]{1,25}"
                       title="Tamaño mínimo: 1. Tamaño máximo: 25"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" maxlength="100" required pattern="[A-Za-z ñÑ À-ú]{2,100}"
                       title="Tamaño mínimo: 2. Tamaño máximo: 100"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="tipo_insumo">Tipo Insumo</label>
                <select name="tipo_insumo" id="tipo_insumo">
                    <?php
                    $query_ti = mysqli_query($conection, "SELECT * FROM tipoinsumo WHERE 1=1 ORDER BY id_tipo_insumo ASC");
                    $result_num = mysqli_num_rows($query_ti);
                    if ($result_num > 0) {
                        while ($result_ti = mysqli_fetch_array($query_ti)) {
                            $value = (int)$result_ti['id_tipo_insumo'];
                            $option = htmlspecialchars($result_ti['descripcion_tipo_insumo']);
                            $select .= "<option value=\"$value\">$option</option>";
                        }
                        echo $select;
                    } else {
                        echo "<option value=\"0\">No hay registros aún</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="divisor_resp"></div>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear unidad
            </button>
        </form>
    </div>

</section>


<?php
if (isset($code) && $code == 4) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Unidades</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Abreviatura Unidad</th>
                <th>Descripción</th>
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
            $query = mysqli_query($conection, "SELECT * FROM unidades WHERE estatus=1
				ORDER BY id_unidad DESC LIMIT $desde,$por_pagina");
            //mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?php echo $data["id_unidad"]; ?></td>
                        <td><?php echo $data["abreviatura_unidad"]; ?></td>
                        <td><?php echo $data["descripcion"]; ?></td>
                        <td>
                            <a class="link_edit" href="editar_unidad.php?id=<?php echo (int)$data["id_unidad"]; ?>"><i
                                        class="fas fa-user-edit"></i> Editar</a>
                            |
                            <a class="link_eliminar"
                               href="eliminar_confirmar_unidad.php?id=<?php echo (int)$data["id_unidad"]; ?>"><i
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
                <li><a href="unidades.php" title="Volver al Listado de Unidades"><i
                                class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>

<?php include "includes/footer.php"; ?>
</body>
</html>
