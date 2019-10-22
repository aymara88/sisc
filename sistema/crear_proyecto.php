<?php
require_once "includes/verifica_sesion.php";

if ($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2) {
    header("location: ./");
}

include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');
include_once('funciones_proyecto.php');
get_post_data();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Nuevo Proyecto</title>
    <script language="javascript" src="js/jquery-3.1.1.min.js"></script>
    <script language="javascript" src="js/proyecto.js"></script>
    <style type="text/css">
        form {
            margin-left: -12px;
        }

        #proyecto_parte_1, #proyecto_parte_2 {
            text-align: left;
        }

        @media screen and (max-width: 640px) and (min-width: 220px) {
            table td {
                width: 100% !important;
                display: block !important;
            }
        }
    </style>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_proyecto">
        <br>
        <h1 class="header_title"><i class="fas fa-chalkboard-teacher fa-2x"></i> Creación de proyectos</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?></div>
            <?php
        }
        ?>
        <div id="proyecto_parte_1">
            <form action="crear_proyecto.php?action=nuevo_proyecto" method="post" name="miForm" style="display:block">
                <fieldset>
                    <legend>PROYECTO</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td colspan="3" width="78%">
                                <div class="ckeckbox_container" style="margin-top:10px;">
                                    <label class="container">
                                        <input type="checkbox" style="width:10px;" value="1" checked="true"
                                               id="nuevo_proyecto" name="nuevo_proyecto"/>
                                        <span id="checkmark_nuevo_proyecto" class="checkmark"></span>
                                        <label for="nuevo_proyecto" style="font-size: 11pt;margin: -4px 0px 0px 15px;"
                                               id="nuevo_proyecto_text">Nuevo Proyecto</label>
                                    </label>
                                </div>
                                <input type="text" name="nombre" id="nombre" maxlength="120"
                                       placeholder="Agregue una nombre para su proyecto" required
                                       title="Introduzca un nombre para su proyecto. Tamaño mínimo: 2. Tamaño máximo: 120"
                                       autofocus
                                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                                    echo "autofocus";
                                } ?> />

                                <?php if (isset($code) && $code == 8) { ?>
                                    <select name="nuevo_proyecto_select" id="nuevo_proyecto_select"
                                            style="width: 100%;">
                                        <?php
                                        $query_proyectos = mysqli_query($conection, "SELECT id_proyecto, nombre_proyecto FROM obras WHERE estatus=1 ORDER BY id_proyecto DESC");
                                        $results_proyectos = mysqli_num_rows($query_proyectos);
                                        ?>
                                        <?php
                                        if ($results_proyectos > 0) {
                                            ?>
                                            <?php
                                            while ($proyecto = mysqli_fetch_array($query_proyectos)) {
                                                ?>
                                                <option value="<?php echo $proyecto["id_proyecto"]; ?>" <?php if ($proyecto["nombre_proyecto"] == $nombre_proyecto) echo 'selected="selected" '; ?>><?php echo $proyecto["nombre_proyecto"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay proyectos</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '<script>
        document.getElementById("nuevo_proyecto").checked = false;
        document.getElementById("nuevo_proyecto").value = 0;
        $("#nuevo_proyecto_text").text("Proyecto Existente");
		$("#nombre").hide();
		$("#nuevo_proyecto_select").show();
		
		setTimeout(function(){ 
		    document.getElementById("btn-signup").style.display = "none";
            document.getElementById("btn-update").style.display = "inline";
            document.getElementById("proyecto_parte_2").style.display = "inline";
            document.getElementById("btn-load").click();
		 }, 100);
		
		$("#nuevo_proyecto_select option:selected").each(function () {

			id_proyecto = $(this).val();

            $.post({

                method: "post",

                url: "includes/getProyecto.php?id="+id_proyecto,

                data: $(this).serialize(),

                success: function(data)

                {

					$("#costo").val(data.costo);

					$("#duracion").val(data.duracion);

					$("#descripcion").val(data.descripcion);

					$("#cliente").val(data.cliente);

					$("#id_proyecto").val(data.id);

					$("#id_proyecto_subobras").val(data.id);

					$(".id_proyecto").val(data.id);

					$("#btn-load").show();

					$("#btn-delete").show();

					LimpiarSubobras();

                }

            });           

		});
</script>';
                                } else { ?>
                                    <select name="nuevo_proyecto_select" id="nuevo_proyecto_select"
                                            style="display:none;width: 100%;">
                                        <?php
                                        $query_proyectos = mysqli_query($conection, "SELECT id_proyecto, nombre_proyecto FROM obras WHERE estatus=1 ORDER BY id_proyecto DESC");
                                        $results_proyectos = mysqli_num_rows($query_proyectos);
                                        ?>
                                        <?php
                                        if ($results_proyectos > 0) {
                                            ?>
                                            <option value="0">Seleccione un proyecto</option>
                                            <?php
                                            while ($proyecto = mysqli_fetch_array($query_proyectos)) {
                                                ?>
                                                <option value="<?php echo $proyecto["id_proyecto"]; ?>"><?php echo $proyecto["nombre_proyecto"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay proyectos</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                <?php } ?>
                                <!--</div>  -->
                            </td>
                            <td>
                                <div class="costo_proyecto">
                                    <label for="costo" style="font-size: 10pt;margin:0;">Costo Estimado</label>
                                    <input type="text" name="costo" id="costo" style="width: 120px;height: 32px;"
                                           disabled
                                           title="Introduzca el costo estimado del proyecto" <?php if (isset($code) && $code == 3) {
                                        echo "autofocus";
                                    } ?> />
                                </div>
                                <div class="duracion_proyecto">
                                    <label for="duracion" style="font-size: 10pt;margin:0;">Duración (Meses)</label>
                                    <input type="text" name="duracion" id="duracion" style="width: 120px;height: 32px;"
                                           disabled
                                           title="Introduzca la duracion estimada para realizar el proyecto" <?php if (isset($code) && $code == 4) {
                                        echo "autofocus";
                                    } ?> />
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td colspan="4">
                                <label for="descripcion" style="font-size: 11pt;margin:0;">Descripción</label>
                                <div class="descripcion_proyecto">
                                    <textarea cols="70" rows="5" style="width:100%" name="descripcion" id="descripcion"
                                              maxlength="500" placeholder="Agregue una descripción para su proyecto"
                                              required
                                              title="Agregue una descripción para su proyecto. Tamaño mínimo: 2. Tamaño máximo: 500"
                                              onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                                        echo "autofocus";
                                    } ?>></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td colspan="4">
                                <label for="cliente" style="font-size: 11pt;margin:0;">Cliente</label>
                                <div class="cliente_proyecto">
                                    <select name="cliente" id="cliente">
                                        <?php
                                        $query_clientes = mysqli_query($conection, "SELECT id_cliente, nombre_cliente FROM clientes WHERE estatus=1");
                                        $results_clientes = mysqli_num_rows($query_clientes);
                                        ?>
                                        <?php
                                        if ($results_clientes > 0) {
                                            while ($cliente = mysqli_fetch_array($query_clientes)) {
                                                ?>
                                                <option value="<?php echo $cliente["id_cliente"]; ?>"><?php echo $cliente["nombre_cliente"] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td colspan="4">
                                <input type="hidden" name="id_proyecto" id="id_proyecto" value="0"/>
                                <button type="submit" name="btn-signup" id="btn-signup" class="btn_save"><i
                                            class="fas fa-plus-circle"></i> Grabar Proyecto
                                </button>
                                <button type="button" name="btn-update" id="btn-update" class="btn_save_inline"
                                        style="display:none;" onclick="GuardarDatosProyecto();"><i
                                            class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <button type="button" name="btn-load" id="btn-load" class="btn_save_inline"
                                        style="display:none;" onclick="CargarDatosProyecto();"><i
                                            class="fas fa-sync-alt"></i> Cargar Sub Obras
                                </button>
                                <button type="button" name="btn-delete" id="btn-delete" class="btn_delete"
                                        style="display:none;" onclick="EliminarProyecto();"><i class="fas fa-trash"></i>
                                    Eliminar Proyecto
                                </button>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td colspan="4">
                                <div id="proyecto_resultado">&nbsp;</div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>

        <div id="proyecto_parte_2" style="display:none">
            <form action="crear_proyecto.php?action=crear_subobra" method="post" name="FormSubobras" id="FormSubobras"
                  style="display:block">
                <fieldset>
                    <legend>SUB-OBRAS</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td>
                                <div class="div_elemento">
                                    <label for="asignadas" style="color:green;">ASIGNADAS</label>
                                </div>
                                <div id="datos_sub_obras" class="div_elemento"></div>
                            </td>
                            <td>
                                <div class="control" style="display:block">
                                    <input type="radio" class="radio_inline" checked="true" value="1"
                                           name="subobras_nuevo" id="subobras_control1"/>
                                    <span>Nuevo</span><br/>
                                    <input type="radio" class="radio_inline" value="2" name="subobras_nuevo"
                                           id="subobras_control2"/>
                                    <span>Existente</span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="nueva_subobra" id="nueva_subobra"
                                       onchange="javascript:this.value=this.value.toUpperCase();"
                                       placeholder="Agregue un nombre a la subobra..."/>
                                <div class="div_elemento" id="subobras_div" style="display:none">
                                    <select name="subobras" id="subobras" style="width: 420px;">
                                        <option value="0">No hay registros.</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="id_proyecto_subobras" id="id_proyecto_subobras"
                                       class="id_proyecto" value="0"/>
                                <button id="button_sub_obra_add" type="submit" name="sndBtnSubObras"
                                        style="background: none;border: none;"><span
                                            class="fa fa-plus-circle fa-3x"
                                            style="cursor:pointer;margin-top: -10px;margin-left: 10px;"
                                            title="Agregar Subobra">&nbsp;</span></button>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Duracion</span>
                                    <input type="text" value="0" name="subobra_duracion" id="subobra_duracion"/>
                                    <span>Meses</span>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important;display:none;" id="clonar_subobra">
                            <td>&nbsp;</td>
                            <td colspan="4">
                                <label class="container" style="margin-bottom: 15px;margin-top:0;">
                                    <span style="font-size:10pt;font-size: 10pt;position: absolute;margin-left: 10px;top: 0;">Crear nueva subobra en base a la actual</span>
                                    <input type="checkbox" style="width:10px;" value="0" id="clonar_subobra_checkbox"
                                           name="clonar_subobra_checkbox"/>
                                    <span class="checkmark_small"></span>
                                </label>
                                <div id="subobra_datos" style="display:none">
                                    <span style="display:inline-block;">Nombre para la nueva subobra</span>
                                    <input type="text" name="clonar_subobra_nombre" id="clonar_subobra_nombre"
                                           onchange="javascript:this.value=this.value.toUpperCase();"
                                           placeholder="Agregar un nuevo nombre para la subobra"
                                           style="display:inline-block;width: 300px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr id="descripcion_subobra" style="background:none !important">
                            <td>
                                <label for="descripcion_subobra" style="font-size: 11pt;">Descripción</label>
                            </td>
                            <td colspan="4">
                                <div class="descripcion_subobra">
                                    <textarea cols="70" rows="2" style="width:100%" name="descripcion_subobra"
                                              id="descripcion_subobra_txt" maxlength="500"
                                              onchange="javascript:this.value=this.value.toUpperCase();"
                                              placeholder="Agregar una descripción para la subobra"
                                              required <?php if (isset($code) && $code == 2) {
                                        echo "autofocus";
                                    } ?>></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td colspan="5">
                                <div id="subobra_resultado"></div>
                            </td>
                        </tr>
<?php echo '<script>
let button_sub_obra_add = document.getElementById("button_sub_obra_add");
button_sub_obra_add.addEventListener("click",function(event) {
    if(document.getElementById("subobras_div").style.display == "none"){
        console.log("aca debo trabajar");
        setTimeout(function() {
            document.getElementById("subobras").options.selectedIndex = 1;
        },100)
    }
})
</script>'; ?>
                    </table>
                </fieldset>
            </form>

            <form action="crear_proyecto.php?action=estimacion_maquinaria" method="post" name="FormEstimacionMaquinaria"
                  id="FormEstimacionMaquinaria" style="display:block">
                <fieldset>
                    <legend>Estimación Maquinaria</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td>
                                <div class="div_elemento">
                                    <label for="maquinaria" style="font-size:12px">Maquinaria</label>
                                </div>
                                <div id="datos_maquinaria" class="div_elemento"></div>
                            </td>
                            <td>
                                <div class="div_elemento" id="maquinaria_div_select">
                                    <select name="maquinaria_select" id="maquinaria_select" style="width: 180px;">
                                        <?php
                                        $query_ma = mysqli_query($conection, "SELECT * FROM maquinaria WHERE estatus=1 ORDER BY descripcion_maquinaria");
                                        $results_ma = mysqli_num_rows($query_ma);
                                        ?>
                                        <?php
                                        if ($results_ma > 0) {
                                            ?>
                                            <option value="0">Seleccionar Maquinaria</option>
                                            <?php
                                            while ($maquinaria = mysqli_fetch_array($query_ma)) {
                                                ?>
                                                <option value="<?php echo $maquinaria["codigo_maquinaria"]; ?>"><?php echo $maquinaria["descripcion_maquinaria"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay registros.</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio</span>
                                    <input type="text" style="width: 90px;" value="0" name="maquinaria_precio"
                                           id="maquinaria_precio"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Tiempo de uso</span>
                                    <input type="text" style="width: 90px;" value="0" name="maquinaria_duracion"
                                           id="maquinaria_duracion"/>
                                    <span>hrs.</span>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio Total</span>
                                    <input type="text" style="width: 90px;" value="0" name="maquinaria_precio_total"
                                           id="maquinaria_precio_total"/>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="id_proyecto_maquinaria" id="id_proyecto_maquinaria"
                                       class="id_proyecto" value="0"/>
                                <input type="hidden" name="id_sub_proyecto_maquinaria" id="id_sub_proyecto_maquinaria"
                                       class="id_sub_proyecto" value="0"/>
                                <button type="submit" name="sndBtnMaquinaria" style="background: none;border: none;">
                                    <span class="fa fa-plus-circle fa-3x"
                                          style="cursor:pointer;margin-top: -10px;margin-left: 10px;"
                                          title="Agregar Maquinaria">&nbsp;</span></button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div id="maquinaria_resultado"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>

            <form action="crear_proyecto.php?action=estimacion_materiales" method="post" name="FormEstimacionMateriales"
                  id="FormEstimacionMateriales" style="display:block">
                <fieldset>
                    <legend>Estimación Materiales</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td colspan="4">
                                <div id="datos_material" class="div_elemento"></div>
                                <div class="div_display_elemento_mat_lab">
                                    <label class="div_display_elemento_mat_lab" for="material" style="font-size:12px">Material</label>
                                </div>
                                <div class="display_elemento_mat" id="material_div_select">
                                    <select class="display_elemento_mat_select" name="material_select1"
                                            id="material_select1" style="width: 180px;">
                                        <option value="0">Selecciona una familia.</option>
                                        <?php
                                        $query_mat = mysqli_query($conection, "SELECT * FROM familias WHERE estatus=1 ORDER BY familia");
                                        $results_mat = mysqli_num_rows($query_mat);
                                        ?>
                                        <?php
                                        if ($results_mat > 0) {
                                            while ($material = mysqli_fetch_array($query_mat)) {
                                                ?>
                                                <option value="<?php echo $material["id_familia"]; ?>"><?php echo $material["familia"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay registros.</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="display_elemento_mat" id="material_div_select3">
                                    <select class="display_elemento_mat_select" name="material_select3"
                                            id="material_select3" style="width: 180px;">
                                        <option value="0">Seleccionar unidad de medida</option>
                                    </select>
                                </div>
                                <div class="display_elemento_mat" id="material_div_select2">
                                    <select class="display_elemento_mat_select" name="material_select2"
                                            id="material_select2" style="width: 180px;">
                                        <option value="0">Seleccionar un material</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:none !important">
                            <td style="display:none;">
                                <div class="div_elemento">
                                    <span>Unidad</span>
                                    <input type="text" style="width: 90px;" value="0" name="material_unidad"
                                           id="material_unidad"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio</span>
                                    <input type="text" style="width: 90px;" value="0" name="material_precio"
                                           id="material_precio"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Cantidad</span>
                                    <input type="text" style="width: 90px;" value="0" name="material_cantidad"
                                           id="material_cantidad"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio Total</span>
                                    <input type="text" style="width: 90px;" value="0" name="material_precio_total"
                                           id="material_precio_total"/>
                                </div>
                            </td>
                            <td align="right">
                                <input type="hidden" name="id_proyecto_material" id="id_proyecto_material"
                                       class="id_proyecto" value="0"/>
                                <input type="hidden" name="id_sub_proyecto_material" id="id_sub_proyecto_material"
                                       class="id_sub_proyecto" value="0"/>
                                <button type="submit" name="sndBtnMaterial" style="background: none;border: none;"><span
                                            class="fa fa-plus-circle fa-3x"
                                            style="cursor:pointer;margin-top: -10px;margin-left: 10px;"
                                            title="Agregar Material">&nbsp;</span></button>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="7">
                                <div id="material_resultado"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>

            <form action="crear_proyecto.php?action=estimacion_mano_obra" method="post" name="FormEstimacionManoObra"
                  id="FormEstimacionManoObra" style="display:block">
                <fieldset>
                    <legend>Estimación Mano de Obra</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td>
                                <div class="div_elemento">
                                    <label for="mano_obra" style="font-size:12px">Mano de Obra</label>
                                </div>
                                <div id="datos_mano_obra" class="div_elemento"></div>
                            </td>
                            <td>
                                <div class="div_elemento" id="mano_obra_div_select">
                                    <select name="mano_obra_select" id="mano_obra_select" style="width: 180px;">
                                        <?php
                                        $query_maob = mysqli_query($conection, "SELECT codigo_mano_obra, descripcion_mano_obra FROM mano_obra WHERE estatus=1 ORDER BY descripcion_mano_obra");
                                        $results_maob = mysqli_num_rows($query_maob);
                                        ?>
                                        <?php
                                        if ($results_maob > 0) {
                                            ?>
                                            <option value="0">Seleccionar Mano de Obra</option>
                                            <?php
                                            while ($maob = mysqli_fetch_array($query_maob)) {
                                                ?>
                                                <option value="<?php echo $maob["codigo_mano_obra"]; ?>"><?php echo $maob["descripcion_mano_obra"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay registros.</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Valor día</span>
                                    <input type="text" style="width: 90px;" value="0" name="mano_obra_dia"
                                           id="mano_obra_dia"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Cantidad</span>
                                    <input type="text" style="width: 90px;" value="0" name="mano_obra_cantidad"
                                           id="mano_obra_cantidad"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio Total</span>
                                    <input type="text" style="width: 90px;" value="0" name="mano_obra_precio_total"
                                           id="mano_obra_precio_total"/>
                                </div>
                            </td>
                            <td align="right">
                                <input type="hidden" name="id_proyecto_manobra" id="id_proyecto_manobra"
                                       class="id_proyecto" value="0"/>
                                <input type="hidden" name="id_sub_proyecto_manobra" id="id_sub_proyecto_manobra"
                                       class="id_sub_proyecto" value="0"/>
                                <button type="submit" name="sndBtnManoObra" style="background: none;border: none;"><span
                                            class="fa fa-plus-circle fa-3x"
                                            style="cursor:pointer;margin-top: -10px;margin-left: 10px;"
                                            title="Agregar Mano de Obra">&nbsp;</span></button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div id="mano_obra_resultado"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>

            <form action="crear_proyecto.php?action=estimacion_herramienta" method="post"
                  name="FormEstimacionHerramienta" id="FormEstimacionHerramienta" style="display:block">
                <fieldset>
                    <legend>Estimación Herramientas</legend>
                    <table border="0">
                        <tr style="background:none !important">
                            <td>
                                <div class="div_elemento">
                                    <label for="herramienta" style="font-size:12px">Herramienta</label>
                                </div>
                                <div id="datos_herramienta" class="div_elemento"></div>
                            </td>
                            <td>
                                <div class="div_elemento" id="herramienta_div_select">
                                    <select name="herramienta_select" id="herramienta_select" style="width: 180px;">
                                        <?php
                                        $query_herramienta = mysqli_query($conection, "SELECT codigo_herramienta, descripcion_herramienta FROM herramientas WHERE estatus=1 ORDER BY descripcion_herramienta");
                                        $results_herramienta = mysqli_num_rows($query_herramienta);
                                        ?>
                                        <?php
                                        if ($results_herramienta > 0) {
                                            ?>
                                            <option value="0">Seleccionar Herramienta</option>
                                            <?php
                                            while ($herramienta = mysqli_fetch_array($query_herramienta)) {
                                                ?>
                                                <option value="<?php echo $herramienta["codigo_herramienta"]; ?>"><?php echo $herramienta["descripcion_herramienta"] ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">Aún no hay registros.</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio</span>
                                    <input type="text" style="width: 90px;" value="0" name="herramienta_precio"
                                           id="herramienta_precio"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Cantidad a usar</span>
                                    <input type="text" style="width: 90px;" value="0" name="herramienta_cantidad"
                                           id="herramienta_cantidad"/>
                                </div>
                            </td>
                            <td>
                                <div class="div_elemento">
                                    <span>Precio Total</span>
                                    <input type="text" style="width: 90px;" value="0" name="herramienta_precio_total"
                                           id="herramienta_precio_total"/>
                                </div>
                            </td>
                            <td align="right">
                                <input type="hidden" name="id_proyecto_herramienta" id="id_proyecto_herramienta"
                                       class="id_proyecto" value="0"/>
                                <input type="hidden" name="id_sub_proyecto_herramienta" id="id_sub_proyecto_herramienta"
                                       class="id_sub_proyecto" value="0"/>
                                <button type="submit" name="sndBtnHerramienta" style="background: none;border: none;">
                                    <span class="fa fa-plus-circle fa-3x"
                                          style="cursor:pointer;margin-top: -10px;margin-left: 10px;"
                                          title="Agregar Herramienta">&nbsp;</span></button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div id="herramienta_resultado"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
            <button type="button" name="sndProyectoFinal" class="btn_save_inline" onclick="AsignarProyecto();"><i
                        class="fas fa-project-diagram"></i> Asignar a Proyecto
            </button>
        </div>
    </div>
</section>
<?php include "includes/footer.php"; ?>
</body>
</html>