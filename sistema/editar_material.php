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
    $codigo = strtoupper($_POST['codigo_original']);
    $material = strtoupper($_POST['codigo']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = 1;
    $unidad = (int)$_POST['unidad'];
    $familia = (int)$_POST['familia'];
    $proveedor = (int)$_POST['proveedor'];
    $costo = (float)$_POST['costo'];

    if (empty($material)) {
        $alert = "Introduce el código del material!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Necesitas agregar una descripción del material";
        $code = 2;
    } else if (!is_numeric($costo)) {
        $alert = "El costo sólo puede tener números!";
        $code = 3;
    } else if (!is_numeric($tipo_insumo)) {
        $alert = "La descripción sólo puede tener números!";
        $code = 4;
    } else if (!is_numeric($unidad)) {
        $alert = "La unidad sólo puede tener números!";
        $code = 5;
    } else if (!is_numeric($familia)) {
        $alert = "La familia sólo puede tener números!";
        $code = 6;
    } else if (!is_numeric($proveedor)) {
        $alert = "El proveedor sólo puede tener números!";
        $code = 7;
    } else {
        $material = mysqli_real_escape_string($conection, $material);
        $codigo = mysqli_real_escape_string($conection, $codigo);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $familia = mysqli_real_escape_string($conection, $familia);
        $proveedor = mysqli_real_escape_string($conection, $proveedor);
        $costo = mysqli_real_escape_string($conection, $costo);

        $query_update = mysqli_query($conection, "UPDATE materiales SET codigo_material='$material', descripcion_material='$descripcion', id_tipo_insumo='$tipo_insumo', id_unidad='$unidad', id_familia='$familia', id_proveedor='$proveedor', costo_material='$costo' WHERE codigo_material='$codigo'");

        if ($query_update) {
            $alert = "Material actualizado correctamente!";
            $code = 9;
        } else {
            $alert = "Error al crear material!";
            $code = 10;
        }
    }
}

// mostrar datos
if (empty(mysqli_real_escape_string($conection, $_GET['id']))) {
    header('Location: materiales.php');
    mysqli_close($conection);
}

$codigo_original = mysqli_real_escape_string($conection, $_GET['id']);
$query_material = mysqli_query($conection, "SELECT * FROM materiales WHERE codigo_material='$codigo_original'");
$result_material = mysqli_num_rows($query_material);

if ($result_material == 0) {
    header('Location: materiales.php');
} else {
    while ($data = mysqli_fetch_array($query_material)) {
        $codigo_original = $data['codigo_material'];
        $descripcion_material = $data['descripcion_material'];
        $id_tipo_insumo = 1; //MATERIALES
        $unidad = $data['id_unidad'];
        $familia = $data['id_familia'];
        $proveedor = $data['id_proveedor'];
        $costo_material = $data['costo_material'];

        /*  Para el Item de Unidad*/
        $option_unidad = '';
        $query_unidad_by_id = mysqli_query($conection, "SELECT * FROM unidades WHERE id_unidad = '$unidad'");
        $result_unidad_by_id = mysqli_fetch_array($query_unidad_by_id);
        $option_unidad = '<option value="' . $unidad . '" select>' . $result_unidad_by_id['descripcion'] . '</option>';

        /*  Para el Item de Familia*/
        $option_familia = '';
        $query_familia_by_id = mysqli_query($conection, "SELECT * FROM familias WHERE id_familia = '$familia'");
        $result_familia_by_id = mysqli_fetch_array($query_familia_by_id);
        $option_familia = '<option value="' . $familia . '" select>' . $result_familia_by_id['familia'] . '</option>';

        /*  Para el Item de Proveedor*/
        $option_proveedor = '';
        $query_proveedor_by_id = mysqli_query($conection, "SELECT * FROM proveedores WHERE id_proveedor = '$proveedor'");
        $result_proveedor_by_id = mysqli_fetch_array($query_proveedor_by_id);
        $option_proveedor = '<option value="' . $proveedor . '" select>' . $result_proveedor_by_id['razon_social'] . '</option>';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Editar material</title>
</head>
<body>

<?php
include "includes/header.php";
//$material = mysqli_real_escape_string($conection, $_GET['id']);
//$querym = mysqli_query($conection, "SELECT * FROM materiales WHERE codigo_material='$material'");
//$resultm = mysqli_fetch_array($querym);
//if ($resultm > 0) {
?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-paint-roller fa-lg"></i> Editar material</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post" name="miForm">
            <div class="divisor_resp">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" id="codigo" value="<?php echo $codigo_original ?>"
                       required minlength="1" maxlength="30"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 1. Tamaño máximo: 30"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" minlength="1" maxlength="100" required
                       title="Tamaño mínimo: 1. Tamaño máximo: 100."
                       value="<?php echo $descripcion_material ?>"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp" id="tipo_insumo_container">
                <label for="tipo_insumo">Tipo Insumo</label>
                <select name="tipo_insumo" id="tipo_insumo">
                    <?php
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

            <div class="divisor_resp">
                <label for="unidad">Unidad</label>
                <select name="unidad" id="unidad" class="noMostrarPrimerItem">
                    <?php
                    echo $option_unidad;
                    $query_u = mysqli_query($conection, "SELECT * FROM unidades WHERE tipo_insumo = 1 ORDER BY id_unidad ASC");
                    $result_unum = mysqli_num_rows($query_u);
                    if ($result_num > 0) {
                        while ($result_u = mysqli_fetch_array($query_u)) {
                            $valueu = (int)$result_u['id_unidad'];
                            if ($valueu == $resultm['id_unidad'])
                                $selectedu = " selected=selected";
                            else
                                $selectedu = "";
                            $optionu = htmlspecialchars($result_u['descripcion']);
                            $selectu .= "<option value=\"{$valueu}\"{$selectedu}>$optionu</option>";
                        }
                        echo $selectu;
                    } else {
                        echo "<option value=\"0\">No hay registros aún</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="familia">Familia</label>
                <select name="familia" id="familia" class="noMostrarPrimerItem">
                    <?php
                    echo $option_familia;
                    $query_f = mysqli_query($conection, "SELECT * FROM familias WHERE 1=1 ORDER BY id_familia ASC");
                    $result_fnum = mysqli_num_rows($query_f);
                    if ($result_fnum > 0) {
                        while ($result_f = mysqli_fetch_array($query_f)) {
                            $valuef = (int)$result_f['id_familia'];
                            $optionf = htmlspecialchars($result_f['familia']);
                            if ($valuef == $resultm['id_familia'])
                                $selectedf = " selected=selected";
                            else
                                $selectedf = "";
                            $selectf .= "<option value=\"{$valuef}\"{$selectedf}>{$optionf}</option>";
                        }
                        echo $selectf;
                    } else {
                        echo "<option value=\"0\">No hay registros aún</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="proveedor">Proveedor</label>
                <select name="proveedor" id="proveedor" class="noMostrarPrimerItem">
                    <?php
                    echo $option_proveedor;
                    $query_p = mysqli_query($conection, "SELECT id_proveedor, razon_social FROM proveedores WHERE 1=1 ORDER BY id_proveedor ASC");
                    $result_pnum = mysqli_num_rows($query_p);
                    if ($result_pnum > 0) {
                        while ($result_p = mysqli_fetch_array($query_p)) {
                            $valuep = (int)$result_p['id_proveedor'];
                            if ($valuep == $resultm['id_proveedor'])
                                $selectedp = " selected=selected";
                            else
                                $selectedp = "";
                            $optionp = htmlspecialchars($result_p['razon_social']);
                            $selectp .= "<option value=\"{$valuep}\"{$selectedp}>{$optionp}</option>";
                        }
                        echo $selectp;
                    } else {
                        echo "<option value=\"0\">No hay registros aún</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="costo">Costo</label>
                <input type="text" name="costo" id="costo" required maxlength="20"
                       pattern='^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$'
                       title="Introduzca el precio del producto. Solo numeros."
                       value="<?php echo $costo_material ?>"
                    <?php if (isset($code) && $code == 3) {
                        echo "autofocus";
                    } ?> />
            </div>
            <div class="divisor_resp">
                <input type="hidden" name="codigo_original" id="codigo_original"
                       value="<?php echo $codigo_original ?>" maxlength="30"/>
            </div>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-pencil"></i> Guardar Cambios
            </button>
        </form>
    </div>

</section>
<?php
//}
include "includes/footer.php";
?>
</body>
</html>