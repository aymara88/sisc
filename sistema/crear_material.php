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
    $material = strtoupper($_POST['codigo']);
    $descripcion = strtoupper($_POST['descripcion']);
    $tipo_insumo = 1; // lo pongo igual a 1 por MATERIALES, removi resultado anterios que tomaba la seleccion del select que decia $_POST['tipo_insumo']
    $unidad = (int)$_POST['unidad'];
    $familia = (int)$_POST['familia'];
    $proveedor = (int)$_POST['proveedor'];
    $costo = (float)$_POST['costo'];

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

    if (empty($material)) {
        $alert = "Introduce el código del material!";
        $code = 1;
    } else if (empty($descripcion)) {
        $alert = "Necesitas agregar una descripción del material";
        $code = 2;
    } else if (!is_numeric($costo)) {
        $alert = "El costo es incorrecto!";
        $code = 3;
    } else if (!is_numeric($tipo_insumo)) {
        $alert = "Tipo de insumo incorrecto!";
        $code = 4;
    } else if (!is_numeric($unidad)) {
        $alert = "La unidad es incorrecta!";
        $code = 5;
    } else if (!is_numeric($familia)) {
        $alert = "La familia es incorrecta!";
        $code = 6;
    } else if (!is_numeric($proveedor)) {
        $alert = "El proveedor es incorrecto!";
        $code = 7;
    } else {
        $material = mysqli_real_escape_string($conection, $material);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $tipo_insumo = mysqli_real_escape_string($conection, $tipo_insumo);
        $unidad = mysqli_real_escape_string($conection, $unidad);
        $familia = mysqli_real_escape_string($conection, $familia);
        $proveedor = mysqli_real_escape_string($conection, $proveedor);
        $costo = mysqli_real_escape_string($conection, $costo);
        $query = mysqli_query($conection, "SELECT * FROM materiales WHERE codigo_material='$material'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = "El material que intentas crear ya existe!";
            $code = 8;
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO materiales(codigo_material, descripcion_material, id_tipo_insumo, id_unidad, id_familia, id_proveedor, costo_material) VALUES('$material','$descripcion','$tipo_insumo', '$unidad', '$familia', '$proveedor', '$costo')");
            if ($query_insert) {
                $alert = "Material creado correctamente!";
                $code = 9;
                //vaciar formulario
                $option_unidad = "";
                $option_proveedor = "";
                $option_familia = "";
                $material = "";
                $descripcion = "";
                $unidad = "";
                $familia = "";
                $proveedor = "";
                $costo = "";
            } else {
                $alert = "Error al crear material!";
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
    <title>Nuevo material</title>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <br>
        <h1><i class="fas fa-paint-roller fa-lg"></i> Alta de materiales</h1>
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
                <input type="text" name="codigo" id="codigo" minlength="1" maxlength="30" required
                       title="Tamaño mínimo: 1. Tamaño máximo: 30."
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> value="<?php if (isset($material) && isset($code) && $code !== 1 && $code !== 8)
                    echo $material
                ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" minlength="1" maxlength="100" required
                       title="Tamaño mínimo: 1. Tamaño máximo: 100."
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> value="<?php if (isset($descripcion) && isset($code) && $code !== 2)
                    echo $descripcion
                ?>"/>
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

            <div class="divisor_resp">
                <label for="unidad">Unidad</label>
                <select name="unidad" id="unidad" class="<?php if (isset($option_unidad) && !empty($option_unidad))
                    echo "noMostrarPrimerItem" ?>">
                    <?php
                    echo $option_unidad;
                    $query_u = mysqli_query($conection, "SELECT * FROM unidades WHERE tipo_insumo = 1 ORDER BY id_unidad ASC");
                    $result_unum = mysqli_num_rows($query_u);
                    if ($result_num > 0) {
                        while ($result_u = mysqli_fetch_array($query_u)) {
                            $valueu = (int)$result_u['id_unidad'];
                            $optionu = htmlspecialchars($result_u['descripcion']);
                            $selectu .= "<option value=\"$valueu\">$optionu</option>";
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
                <select name="familia" id="familia" class="<?php if (isset($option_familia) && !empty($option_familia))
                    echo "noMostrarPrimerItem" ?>">
                    <?php
                    echo $option_familia;
                    $query_f = mysqli_query($conection, "SELECT * FROM familias WHERE 1=1 ORDER BY id_familia ASC");
                    $result_fnum = mysqli_num_rows($query_f);
                    if ($result_fnum > 0) {
                        while ($result_f = mysqli_fetch_array($query_f)) {
                            $valuef = (int)$result_f['id_familia'];
                            $optionf = htmlspecialchars($result_f['familia']);
                            $selectf .= "<option value=\"$valuef\">$optionf</option>";
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
                <select name="proveedor" id="proveedor"
                        class="<?php if (isset($option_proveedor) && !empty($option_proveedor))
                            echo "noMostrarPrimerItem" ?>">
                    <?php
                    echo $option_proveedor;
                    $query_p = mysqli_query($conection, "SELECT id_proveedor, razon_social FROM proveedores WHERE 1=1 ORDER BY id_proveedor ASC");
                    $result_pnum = mysqli_num_rows($query_p);
                    if ($result_pnum > 0) {
                        while ($result_p = mysqli_fetch_array($query_p)) {
                            $valuep = (int)$result_p['id_proveedor'];
                            $optionp = htmlspecialchars($result_p['razon_social']);
                            $selectp .= "<option value=\"$valuep\">$optionp</option>";
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
                <input type="number" name="costo" id="costo" required min="0.01" max="999999.99"
                       step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                       title="Introduzca el precio del producto. Solo numeros."
                    <?php if (isset($code) && $code == 3) {
                        echo "autofocus";
                    } ?> value="<?php if (isset($costo) && isset($code) && $code !== 3) echo $costo ?>"/>
            </div>

            <div class="divisor_resp"></div>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-plus-circle"></i> Crear material
            </button>
        </form>
    </div>

</section>
<?php
if (isset($code) && $code == 9) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Materiales</h1>

        <table>
            <tr>
                <th>Código</th>
                <th width="280">Descripción</th>
                <th>Unidad</th>
                <th>Familia</th>
                <th>Costo</th>
                <th>Proveedor</th>
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

            $query = mysqli_query($conection, "SELECT m.*, u.descripcion, ti.descripcion_tipo_insumo, f.familia, p.razon_social
				FROM materiales m
					LEFT JOIN unidades u
					ON (u.id_unidad=m.id_unidad)
					LEFT JOIN tipoinsumo ti
					ON (m.id_tipo_insumo=ti.id_tipo_insumo)
					LEFT JOIN familias f
					ON (m.id_familia=f.id_familia)
					LEFT JOIN proveedores p
					ON (m.id_proveedor=p.id_proveedor)
				WHERE m.estatus=1
				ORDER BY codigo_material DESC LIMIT $desde,$por_pagina");
            //mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $data["codigo_material"] = htmlspecialchars($data["codigo_material"]);
                    ?>
                    <tr>
                        <td><?php echo $data["codigo_material"]; ?></td>
                        <td><?php echo $data["descripcion_material"]; ?></td>
                        <td><?php echo $data["descripcion"]; ?></td>
                        <td><?php echo $data["familia"]; ?></td>
                        <td><?php echo "$" . number_format($data["costo_material"], 2, ".", ","); ?></td>
                        <td><?php echo $data["razon_social"]; ?></td>
                        <td>
                            <a class="link_edit"
                               href="editar_material.php?id=<?php echo $data["codigo_material"]; ?>"><i
                                        class="fas fa-user-edit"></i> Editar</a>
                            |
                            <a class="link_eliminar"
                               href="eliminar_confirmar_material.php?id=<?php echo $data["codigo_material"]; ?>"><i
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
                <li><a href="materiales.php" title="Volver al Listado de Materiales"><i
                                class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>

<?php include "includes/footer.php"; ?>
</body>
</html>