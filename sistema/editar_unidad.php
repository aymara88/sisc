<?php
require_once "includes/verifica_sesion.php";

include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');

if (isset($_POST['btn-signup'])) {
    $alert = '';
    $id_unidad = (int)$_POST['id_unidad'];
    $unidad = strtoupper($_POST['unidad']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = strtoupper($_POST['tipo_insumo']);

    if (empty($unidad)) {
        $alert = "Introduce el nombre de la nueva unidad!";
        $code = 1;
    } else if (!preg_match("/^[a-zA-Z0-9 ]+$/i", $unidad)) {
        $alert = "La unidad sólo puede tener letras y números!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Debes agregar una descripción correcta";
        $code = 2;
    } else if (!preg_match("/^[a-zA-Z0-9 ]+$/i", $descripcion)) {
        $alert = "La descripción sólo puede tener letras y números!";
        $code = 2;
    } else {
        $id_unidad = mysqli_real_escape_string($conection, $id_unidad);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);

        $query_update = mysqli_query($conection, "UPDATE unidades SET abreviatura_unidad='$unidad', descripcion='$descripcion', tipo_insumo='$tipo_insumo' WHERE id_unidad='$id_unidad'");
        if ($query_update) {
            $alert = "Unidad editada correctamente!";
            $code = 4;
        } else {
            $alert = "Error al crear unidad!";
            $code = 5;
        }

    }
}

//mostrar datos de tipo de insumo
$id = (int)$_GET['id'];
$query_fam = mysqli_query($conection, "SELECT * FROM unidades WHERE id_unidad='$id' LIMIT 1") or die ("No existe la unidad seleccionada");
$result = mysqli_fetch_array($query_fam);
if ($result > 0) {
    $id_unidad = (int)$result['id_unidad'];
    $unidad = htmlspecialchars($result['abreviatura_unidad']);
    $descripcion = htmlspecialchars($result['descripcion']);
    $id_tipo_insumo = htmlspecialchars($result['tipo_insumo']);
}
/*  Para el Item de Tipo de Insumo*/

$option_tipo_insumo = '';
$query_tipo_insumo_by_id = mysqli_query($conection, "SELECT * FROM tipoinsumo WHERE id_tipo_insumo = '$id_tipo_insumo'");
$result_tipo_insumo_by_id = mysqli_fetch_array($query_tipo_insumo_by_id);
$option_tipo_insumo = '<option value="' . $id_tipo_insumo . '" select>' . $result_tipo_insumo_by_id['descripcion_tipo_insumo'] . '</option>';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Editar Unidad</title>
</head>
<body>

<?php
include "includes/header.php";
$id = (int)$_GET['id'];
$query_fam = mysqli_query($conection, "SELECT * FROM unidades WHERE id_unidad='$id' LIMIT 1") or die ("No existe la unidad seleccionada");
$result = mysqli_fetch_array($query_fam);
if ($result > 0) {
    $id_unidad = (int)$result['id_unidad'];
    $unidad = htmlspecialchars($result['abreviatura_unidad']);
    $descripcion = htmlspecialchars($result['descripcion']);
    $id_tipo_insumo = htmlspecialchars($result['id_tipo_insumo']);
    ?>
    <section id="container">
        <div class="form_register">
            <br>
            <h1><i class="fas fa-ruler fa-lg"></i> Editar unidad</h1>
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
                    <input type="text" name="unidad" id="unidad" value="<?php echo $unidad ?>" maxlength="25" required
                           pattern="{1,25}"
                           title="Tamaño mínimo: 1. Tamaño máximo: 25."
                           onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                        echo "autofocus";
                    } ?> />
                </div>

                <div class="divisor_resp">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion ?>"
                           maxlength="100" required pattern="{1,100}" title="Tamaño mínimo: 1. Tamaño máximo: 100."
                           onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                        echo "autofocus";
                    } ?> />
                </div>

                <div class="divisor_resp">
                    <label for="tipo_insumo">Tipo Insumo</label>
                    <select name="tipo_insumo" id="tipo_insumo" class="noMostrarPrimerItem">
                        <?php

                        echo $option_tipo_insumo;

                        $query_ti = mysqli_query($conection, "SELECT * FROM tipoinsumo WHERE 1=1 ORDER BY id_tipo_insumo ASC");
                        $result_num = mysqli_num_rows($query_ti);
                        if ($result_num > 0) {
                            while ($result_ti = mysqli_fetch_array($query_ti)) {
                                $value = (int)$result_ti['id_tipo_insumo'];
                                if ($value == $resultm['id_tipo_insumo'])
                                    $selected = " selected=selected";
                                else
                                    $selected = "";
                                $option = htmlspecialchars($result_ti['descripcion_tipo_insumo']);
                                $select .= "<option value=\"{$value}\"{$selected}>{$option}</option>";
                            }
                            echo $select;
                        } else {
                            echo "<option value=\"0\">No hay registros aún</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="divisor_resp"></div>
                <input type="hidden" name="id_unidad" id="id_unidad" value="<?php echo $id_unidad ?>"/>
                <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Guardar
                    Cambios
                </button>
            </form>
        </div>

    </section>
    <?php
}
include "includes/footer.php";
?>
</body>
</html>