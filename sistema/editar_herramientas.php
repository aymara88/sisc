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
    $codigo = strtoupper($_POST['codigo_herramienta']);
    $herramientas = strtoupper($_POST['codigo']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = $_POST['tipo_insumo'];
    $unidad = (int)$_POST['unidad'];
    $costo = (float)$_POST['costo'];

    if (empty($herramientas)) {
        $alert = "Introduce el código del herramientas!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Necesitas agregar una descripción del herramientas";
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
    } else {
        $herramientas = mysqli_real_escape_string($conection, $herramientas);
        $codigo = mysqli_real_escape_string($conection, $codigo);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $costo = mysqli_real_escape_string($conection, $costo);
        $query_update = mysqli_query($conection, "UPDATE herramientas SET codigo_herramienta='$herramientas', descripcion_herramienta='$descripcion', id_tipo_insumo='$tipo_insumo', id_unidad='$unidad', costo_herramienta='$costo' WHERE codigo_herramienta='$codigo'");
        if ($query_update) {
            $alert = "Herramienta actualizada correctamente!";
            $code = 9;
        } else {
            $alert = "Error al editar herramienta!";
            $code = 10;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Editar herramientas</title>
</head>
<body>

<?php
include "includes/header.php";
$herramientas = mysqli_real_escape_string($conection, $_GET['id']);
$querym = mysqli_query($conection, "SELECT * FROM herramientas WHERE codigo_herramienta='$herramientas'");
$resultm = mysqli_fetch_array($querym);
if ($resultm > 0) {
    ?>
    <section id="container">
        <div class="form_register">
            <br>
            <h1><i class="fas fa-tools fa-lg"></i> Editar herramientas</h1>
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
                    <input type="text" name="codigo" id="codigo" maxlength="30" required
                           pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{2,30}"
                           title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 30"
                           value="<?php echo $resultm['codigo_herramienta'] ?>"
                           onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                        echo "autofocus";
                    } ?> />
                </div>

                <div class="divisor_resp">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" maxlength="100" required
                           pattern="[A-Za-z0-9 ñÑ À-ú]{2,100}"
                           title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 100"
                           value="<?php echo $resultm['descripcion_herramienta'] ?>"
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
                    <select name="unidad" id="unidad">
                        <?php
                        $query_u = mysqli_query($conection, "SELECT * FROM unidades WHERE tipo_insumo = 4 ORDER BY id_unidad ASC");
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
                    <label for="costo">Costo</label>
                    <input type="number" name="costo" id="costo" required min="0.01" max="999999.99"
                           step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                           title="Introduzca el precio del producto. Solo numeros." value="<?php echo $resultm['costo_herramienta'] ?>"
                        <?php if (isset($code) && $code == 3) {
                            echo "autofocus";
                        } ?> />
                </div>

                <div class="divisor_resp"></div>
                <input type="hidden" value="<?php echo $resultm['codigo_herramienta'] ?>" name="codigo_herramienta"
                       id="codigo_herramienta"/>
                <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-pencil"></i> Guardar Cambios
                </button>
            </form>
        </div>

    </section>
    <?php
} else header("location: herramientas.php");
include "includes/footer.php";
?>
</body>
</html>