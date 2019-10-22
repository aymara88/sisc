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
    $maquinaria = strtoupper($_POST['codigo']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = 3;
    $unidad = (int)$_POST['unidad'];
    $costo = (float)$_POST['costo'];

    /*  Para el Item de Unidad*/
    $option_unidad = '';
    $query_unidad_by_id = mysqli_query($conection, "SELECT * FROM unidades WHERE id_unidad = '$unidad'");
    $result_unidad_by_id = mysqli_fetch_array($query_unidad_by_id);
    $option_unidad = '<option value="' . $unidad . '" select>' . $result_unidad_by_id['descripcion'] . '</option>';


    if (empty($maquinaria)) {
        $alert = "Introduce el código!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Necesitas agregar una descripción";
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
        $maquinaria = mysqli_real_escape_string($conection, $maquinaria);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $costo = mysqli_real_escape_string($conection, $costo);
        $query = mysqli_query($conection, "SELECT * FROM maquinaria WHERE codigo_maquinaria='$maquinaria'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = "La maquinaria que intentas crear ya existe!";
            $code = 8;
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO maquinaria(codigo_maquinaria, descripcion_maquinaria, id_tipo_insumo, id_unidad, costo_maquinaria) VALUES('$maquinaria','$descripcion','$tipo_insumo', '$unidad', '$costo')");
            if ($query_insert) {
                $alert = "maquinaria creada correctamente!";
                $code = 9;
                //vaciar formulario
                $unidad = "";
                $maquinaria = "";
                $descripcion = "";
                $costo = "";
                $option_unidad = "";
            } else {
                $alert = "Error al crear maquinaria!";
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
    <title>Nueva maquinaria</title>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-truck-monster fa-lg"></i> Crear nueva maquinaria</h1>
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
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?>
                       value="<?php if (isset($maquinaria) && isset($code) && $code !== 1 && $code !== 8) echo $maquinaria ?>"/>
            </div>
            <div class="divisor_resp">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" maxlength="100" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{2,100}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 100"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> value="<?php if (isset($descripcion) && isset($code) && $code !== 2) echo $descripcion ?>"/>
            </div>

            <div class="divisor_resp" id="tipo_insumo_container">
                <label for="tipo_insumo">Tipo Insumo</label>
                <select name="tipo_insumo" id="tipo_insumo">
                    <?php
                    $query_ti = mysqli_query($conection, "SELECT * FROM tipoinsumo WHERE 1=1 ORDER BY id_tipo_insumo ASC");
                    $result_num = mysqli_num_rows($query_ti);
                    if ($result_num > 0) {
                        $select = "";
                        while ($result_ti = mysqli_fetch_array($query_ti)) {
                            $value = (int)$result_ti['id_tipo_insumo'];
                            $option = htmlspecialchars($result_ti['descripcion_tipo_insumo']);
                            if ($value == 3)
                                $selected = " selected=\"selected\"";
                            else
                                $selected = "";
                            $select .= "<option value=\"$value\"$selected>$option</option>";
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
                <select name="unidad" id="unidad"
                        class="<?php if (isset($option_unidad) && !empty($option_unidad)) echo "noMostrarPrimerItem" ?>">
                    <?php
                    echo $option_unidad;
                    $query_u = mysqli_query($conection, "SELECT * FROM unidades WHERE tipo_insumo = 3 ORDER BY id_unidad ASC");
                    $result_unum = mysqli_num_rows($query_u);
                    if ($result_num > 0) {
                        $selectu = "";
                        while ($result_u = mysqli_fetch_array($query_u)) {
                            $valueu = (int)$result_u['id_unidad'];
                            $optionu = htmlspecialchars($result_u['descripcion']);
                            if ($valueu == 12)
                                $selectedu = " selected=\"selected\"";
                            else
                                $selectedu = "";
                            $selectu .= "<option value=\"$valueu\"$selectedu>$optionu</option>";
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
                       title="Introduzca el precio del producto. Solo numeros."
                    <?php if (isset($code) && $code == 3) {
                        echo "autofocus";
                    } ?> value="<?php if (isset($costo) && isset($code) && $code !== 3) echo $costo ?>"/>
            </div>
            <div class="divisor_resp"></div>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear maquinaria
            </button>
        </form>
    </div>

</section>
<?php
if (isset($code) && $code == 9) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Maquinaria</h1>

        <table>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Costo</th>
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
            $query = mysqli_query($conection, "SELECT m.*, u.descripcion, ti.descripcion_tipo_insumo
				FROM maquinaria m
					LEFT JOIN unidades u
					ON (u.id_unidad=m.id_unidad)
					LEFT JOIN tipoinsumo ti
					ON (m.id_tipo_insumo=ti.id_tipo_insumo)
				WHERE m.estatus=1
				ORDER BY codigo_maquinaria DESC LIMIT $desde,$por_pagina");

            //mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $data["codigo_maquinaria"] = htmlspecialchars($data["codigo_maquinaria"]);
                    ?>
                    <tr>
                        <td><?php echo $data["codigo_maquinaria"]; ?></td>
                        <td><?php echo $data["descripcion_maquinaria"]; ?></td>
                        <td><?php echo $data["descripcion"]; ?></td>
                        <td><?php echo "$" . number_format($data["costo_maquinaria"], 2, ".", ","); ?></td>
                        <td>
                            <a class="link_edit"
                               href="editar_maquinaria.php?id=<?php echo $data["codigo_maquinaria"]; ?>"><i
                                        class="fas fa-user-edit"></i> Editar</a>
                            |
                            <a class="link_eliminar"
                               href="eliminar_confirmar_maquinaria.php?id=<?php echo $data["codigo_maquinaria"]; ?>"><i
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
                <li><a href="maquinaria.php" title="Volver al Listado de Maquinarias"><i
                                class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>


<?php include "includes/footer.php"; ?>
</body>
</html>