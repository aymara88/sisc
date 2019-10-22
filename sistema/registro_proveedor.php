<?php
require_once "includes/verifica_sesion.php";
include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');

if (isset($_POST['btn-signup'])) {
    $alert = '';
    $razon_social = strtoupper($_POST['razon_social']);
    $rfc = strtoupper($_POST['rfc']);
    $tipo_persona = $_POST['tipo_persona'];
    $calle = strtoupper($_POST['calle']);
    $numero = intval($_POST['numero']);
    $colonia = strtoupper($_POST['colonia']);
    $codigo_postal = intval($_POST['codigo_postal']);
    $id_estado = $_POST['estado'];
    $id_municipio = $_POST['municipio'];
    $id_localidad = $_POST['localidad'];
    $telefono = intval($_POST['telefono']);
    $email = trim($_POST['email']);
    $website = trim($_POST['website']);
    $resultado_rfc = strtoupper($_POST['resultado_rfc']);

    /*  Para el Item de Tipo de Persona*/
    $option_tipo_persona = '';
    $query_tipo_persona_by_id = mysqli_query($conection, "SELECT * FROM tipo_persona WHERE id_tipo_persona = '$tipo_persona'");
    $result_tipo_persona_by_id = mysqli_fetch_array($query_tipo_persona_by_id);
    $option_tipo_persona = '<option value="' . $tipo_persona . '" select>' . $result_tipo_persona_by_id['tipo_persona'] . '</option>';

    /*  Para el Item de Estados*/
    $option_estado = '';
    $query_estado_by_id = mysqli_query($conection, "SELECT * FROM estados WHERE id_estado = '$id_estado'");
    $result_estado_by_id = mysqli_fetch_array($query_estado_by_id);
    $option_estado = '<option value="' . $id_estado . '" select>' . $result_estado_by_id['nombre_estado'] . '</option>';

    /*  Para el Item de Municipios*/
    $option_municipio = '';
    $query_municipio_by_id = mysqli_query($conection, "SELECT * FROM municipios WHERE id_municipio = '$id_municipio'");
    $result_municipio_by_id = mysqli_fetch_array($query_municipio_by_id);
    $option_municipio = '<option value="' . $id_municipio . '" select>' . $result_municipio_by_id['nombre_municipio'] . '</option>';

    /*  Para el Item de Localidades*/
    $option_localidad = '';
    $query_localidad_by_id = mysqli_query($conection, "SELECT * FROM localidades WHERE id_localidad = '$id_localidad'");
    $result_localidad_by_id = mysqli_fetch_array($query_localidad_by_id);
    $option_localidad = '<option value="' . $id_localidad . '" select>' . $result_localidad_by_id['nombre_localidad'] . '</option>';

    if ($tipo_persona == 1) {
        if (empty($razon_social)) {
            $alert = "Introduce tu nombre!";
            $code = 1;
        } else if (!preg_match("/^[a-zA-ZÀ-ÿ ñÑ]+(\s*[a-zA-ZÀ-ÿ ñÑ]*)*[a-zA-ZÀ-ÿ ñÑ]+$/i", $razon_social)) {
            $alert = "Introduce correctamente tu nombre!";
            $code = 1;
        }
    } else {
        if (empty($razon_social)) {
            $alert = "Introduce tu Razón Social!";
            $code = 1;
        }
    }

    if (empty($rfc)) {
        $alert = "Introduce el RFC del proveedor!";
        $code = 2;
    } else if (!preg_match("/^[0-9a-zA-ZñÑ\s]+$/i", $rfc)) {
        $alert = "Introduce sólo letras o números para el RFC del proveedor!";
        $code = 2;
    } else if ($resultado_rfc == 0) { //aca estamos comprobando a traves del hidden input el resultado de la funcion javascript que valida el RFC
        $alert = "El RFC introducido no es valido";
        $code = 2;
    } else if (empty($calle)) {
        $alert = "Introduce la calle del proveedor!";
        $code = 3;
    } else if (!preg_match("/^[0-9a-zA-ZÀ-ÿ ñÑáéíóúÁÉÍÓÚ\s]+$/i", $calle)) {
        $alert = "La calle sólo puede llevar letras o números";
        $code = 3;
    } else if (empty($numero)) {
        $alert = "Introduce el número de la vivienda del proveedor!";
        $code = 4;
    } else if (!is_numeric($numero)) {
        $alert = "Introduce sólo números para el número de vivienda del proveedor!";
        $code = 4;
    } else if (empty($colonia)) {
        $alert = "Introduce la colonia del proveedor!";
        $code = 5;
    } else if (!preg_match("/^[0-9a-zA-ZÀ-ÿ ñÑ\s]+$/i", $colonia)) {
        $alert = "Introduce sólo letras y números para la colonia";
        $code = 5;
    } else if (empty($codigo_postal)) {
        $alert = "Introduce el código postal!";
        $code = 6;
    } else if (!preg_match("/^[0-9]+$/i", $codigo_postal)) {
        $alert = "Introduce sólo números para el código postal!";
        $code = 6;
    } else if (empty($id_localidad) || !is_numeric($id_localidad)) {
        $alert = "Localidad incorrecta!";
        $code = 7;
    } else if (empty($telefono)) {
        $alert = "Debes agregar un número de teléfono!";
        $code = 8;
    } else if (!is_numeric($telefono)) {
        $alert = "Introduce sólo números para el número de teléfono del proveedor!";
        $code = 8;
    } else if (!empty($telefono) && strlen($telefono) != 10) {
        $alert = "Deben ser 10 dígitos para el número de teléfono!";
        $code = 8;
    } else if (empty($email)) {
        $alert = "Introduce una direción de correo electrónico!";
        $code = 9;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "Introduce una direción de correo electrónico válida para el proveedor!";
        $code = 9;
    } /*else if (!empty($email) && !preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i", $email)) {
        $alert = "Dirección de Email no es válida!";
        $code = 9;
    }*/ else if (!empty($website) && !preg_match("#(https?://([-\w\.]+)+(:\d+)?(/([\w/_'-\.]*(\?\S+)?)?)?)#is", $website)) {
        $alert = "Sitio web del proveedor incorrecto!";
        $code = 10;
    } else {
        $razon_social = mysqli_real_escape_string($conection, $razon_social);
        $rfc = mysqli_real_escape_string($conection, $rfc);
        $calle = mysqli_real_escape_string($conection, $calle);
        $numero = mysqli_real_escape_string($conection, $numero);
        $colonia = mysqli_real_escape_string($conection, $colonia);
        $codigo_postal = mysqli_real_escape_string($conection, $codigo_postal);
        $id_localidad = mysqli_real_escape_string($conection, $id_localidad);
        $telefono = mysqli_real_escape_string($conection, $telefono);
        $email = mysqli_real_escape_string($conection, $email);
        $website = mysqli_real_escape_string($conection, $website);
        $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE rfc='$rfc' LIMIT 1");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = "El proveedor ya existe en su base de datos!";
            $code = 11;
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO proveedores(razon_social, rfc, calle, numero, colonia, cp, id_localidad ,telefono , mail, pagina_web, id_tipo_persona) VALUES('$razon_social', '$rfc', '$calle', '$numero', '$colonia', '$codigo_postal', '$id_localidad', '$telefono', '$email', '$website','$tipo_persona') ");
            if ($query_insert) {
                $alert = "Proveedor creado correctamente!";
                $code = 12;

                //vaciar formulario
                $option_tipo_persona = "";
                $option_municipio = "";
                $option_estado = "";
                $option_localidad = "";
                $razon_social = "";
                $tipo_persona = "";
                $rfc = "";
                $id_localidad = "";
                $colonia = "";
                $calle = "";
                $numero = "";
                $codigo_postal = "";
                $telefono = "";
                $email = "";
                $website = "";

            } else {
                $alert = "Error al crear proveedor!";
                $code = 13;
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
    <title>Registro de Proveedores</title>
    <script language="javascript" src="js/jquery-3.1.1.min.js"></script>
    <script language="javascript">
        $(document).ready(function () {
            $("#estado").change(function () {
                $('#localidad').find('option').remove().end().append('<option value="0"></option>').val('0');
                $("#estado option:selected").each(function () {
                    id_estado = $(this).val();
                    $.post("includes/getMunicipio.php", {id_estado: id_estado}, function (data) {
                        $("#municipio").html(data);
                    });
                });
            })
        });

        $(document).ready(function () {
            $("#municipio").change(function () {
                $("#municipio option:selected").each(function () {
                    id_municipio = $(this).val();
                    $.post("includes/getLocalidad.php", {id_municipio: id_municipio}, function (data) {
                        $("#localidad").html(data);
                    });
                });
            })
        });

        //Función para validar un RFC
        // Devuelve el RFC sin espacios ni guiones si es correcto
        // Devuelve false si es inválido
        // (debe estar en mayúsculas, guiones y espacios intermedios opcionales)
        function rfcValido(rfc, aceptarGenerico = true) {
            const re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
            var validado = rfc.match(re);
            if (!validado)  //Coincide con el formato general del regex?
                return false;
            //Separar el dígito verificador del resto del RFC
            const digitoVerificador = validado.pop(),
                rfcSinDigito = validado.slice(1).join(''),
                len = rfcSinDigito.length,
                //Obtener el digito esperado
                diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
                indice = len + 1;
            var suma,
                digitoEsperado;
            if (len == 12) suma = 0
            else suma = 481; //Ajuste para persona moral
            for (var i = 0; i < len; i++)
                suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
            digitoEsperado = 11 - suma % 11;
            if (digitoEsperado == 11) digitoEsperado = 0;
            else if (digitoEsperado == 10) digitoEsperado = "A";
            //El dígito verificador coincide con el esperado?
            // o es un RFC Genérico (ventas a público general)?
            if ((digitoVerificador != digitoEsperado)
                && (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000"))
                return false;
            else if (!aceptarGenerico && rfcSinDigito + digitoVerificador == "XEXX010101000")
                return false;
            return rfcSinDigito + digitoVerificador;
        }

        //Handler para el evento cuando cambia el input
        // -Lleva la RFC a mayúsculas para validarlo
        // -Elimina los espacios que pueda tener antes o después
        function validarInput(input) {
            var rfc = input.value.trim().toUpperCase(),
                resultado = document.getElementById("resultado"),
                valido;
            var rfcCorrecto = rfcValido(rfc);   // ⬅️ Acá se comprueba
            if (rfcCorrecto) {
                valido = "Válido";
                resultado.classList.add("ok");
                let input_hidden = document.getElementById('resultado_rfc')
                input_hidden.value = '1';
            } else {
                valido = "No válido"
                resultado.classList.remove("ok");
                let input_hidden = document.getElementById('resultado_rfc')
                input_hidden.value = '0';
            }
            resultado.innerText = "Formato: " + valido;
        }
    </script>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">
    <div class="form_register">
        <h1><i class="fas fa-truck-container fa-lg"></i> Registro de proveedores</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post" name="miForm">

            <div class="divisor_resp">
                <label for="tipo_persona">Tipo persona</label>
                <?php
                $query_tipo_persona = mysqli_query($conection, "SELECT * FROM tipo_persona");
                $result_tipo_persona = mysqli_num_rows($query_tipo_persona);
                ?>
                <select name="tipo_persona" id="tipo_persona"
                        class="<?php if (isset($option_tipo_persona) && !empty($option_tipo_persona)) {
                            echo "noMostrarPrimerItem";
                        } else {
                            echo '';
                        } ?>">
                    <?php
                    echo $option_tipo_persona;
                    if ($result_tipo_persona > 0) {
                        while ($tipo_persona = mysqli_fetch_array($query_tipo_persona)) {
                            ?>
                            <option value="<?php echo $tipo_persona["id_tipo_persona"]; ?>"><?php echo $tipo_persona["tipo_persona"] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" id="rfc" maxlength="13" required
                       pattern="[0-9A-Za-z-9À-ÿ\u00f1\u00d1 ]{12,13}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 12. Tamaño máximo: 13"
                       onchange="javascript:this.value=this.value.toUpperCase();" oninput="validarInput(this);"
                       onblur="validarInput(this);" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> value="<?php if (isset($rfc) && isset($code) && $code !== 2 && $code !== 11) {
                    echo $rfc;
                } else {
                    echo '';
                } ?>"/>
                <label id="resultado"></label>
                <input type="hidden" name="resultado_rfc" id="resultado_rfc" value="0"/>
            </div>

            <div class="divisor_resp">
                <label for="razon_social">Razón Social</label>
                <input type="text" name="razon_social" id="razon_social" maxlength="120" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{10,120}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 10. Tamaño máximo: 120" autofocus
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> value="<?php if (isset($razon_social) && isset($code) && $code !== 1) {
                    echo $razon_social;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="calle">Calle</label>
                <input type="text" name="calle" id="calle" maxlength="70" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{2,70}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 70"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 3) {
                    echo "autofocus";
                } ?> value="<?php if (isset($calle) && isset($code) && $code !== 3) {
                    echo $calle;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" maxlength="10" required
                       pattern="[0-9]{1,4}"
                       title="Introduzca sólo números. Tamaño mínimo: 1. Tamaño máximo: 4" <?php if (isset($code) && $code == 4) {
                    echo "autofocus";
                } ?> value="<?php if (isset($numero) && isset($code) && $code !== 4) {
                    echo $numero;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="colonia">Colonia</label>
                <input type="text" name="colonia" id="colonia" maxlength="60" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{5,60}"
                       title="Introduzca el nombre de la Colonia. Tamaño mínimo: 5. Tamaño máximo: 60"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 5) {
                    echo "autofocus";
                } ?> value="<?php if (isset($colonia) && isset($code) && $code !== 5) {
                    echo $colonia;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" name="codigo_postal" id="codigo_postal" maxlength="5" required pattern="[0-9 ]{1,5}"
                       title="Introduzca sólo números. Tamaño mínimo: 1. Tamaño máximo: 5" <?php if (isset($code) && $code == 6) {
                    echo "autofocus";
                } ?> value="<?php if (isset($codigo_postal) && isset($code) && $code !== 6) {
                    echo $codigo_postal;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required class="<?php if (isset($option_estado)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
                    <?php
                    echo $option_estado;
                    ?>
                    <option value="">Seleccionar Estado</option>
                    <?php
                    $query_e = mysqli_query($conection, "SELECT * FROM estados ORDER BY id_estado ASC");
                    $result_nume = mysqli_num_rows($query_e);
                    if ($result_nume > 0) {
                        while ($result_e = mysqli_fetch_array($query_e)) {
                            $valuee = (int)$result_e['id_estado'];
                            $optione = htmlspecialchars($result_e['nombre_estado']);
                            $selecte .= "<option value=\"$valuee\">$optione</option>";
                        }
                        echo $selecte;
                    } else {
                        echo "<option value=\"0\">No hay registros aún</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="municipio">Municipio</label>
                <select name="municipio" id="municipio" required class="<?php if (isset($option_municipio)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
                    <?php
                    echo $option_municipio;
                    ?>
                    <option value="">Seleccionar Municipio</option>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="localidad">Localidad</label>
                <select name="localidad" id="localidad" required class="<?php if (isset($option_localidad)) {
                    echo "noMostrarPrimerItem";
                } else {
                    echo '';
                } ?>">
                    <?php
                    echo $option_localidad;
                    ?>
                    <option value="">Seleccionar Localidad</option>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" maxlength="10" required pattern="[0-9 ]{10,10}"
                       title="Introduzca sólo números. Tamaño: 10 dígitos" <?php if (isset($code) && $code == 8) {
                    echo "autofocus";
                } ?> value="<?php if (isset($telefono) && isset($code) && $code !== 8) {
                    echo $telefono;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="email">Correo Electrónico</label>
                <input type="text" name="email" id="email" maxlength="70"
                       required
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       title="Ejemplo: dina@gmail.com" <?php if (isset($code) && $code == 9) {
                    echo "autofocus";
                } ?> value="<?php if (isset($email) && isset($code) && $code !== 9) {
                    echo $email;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <div class="divisor_resp">
                <label for="website">Página Web</label>
                <input type="text" name="website" id="website" maxlength="120" pattern="[A-Za-z0-9!?-.]{0,120}"
                       title="Introduzca su Página Web. Tamaño máximo: 120" <?php if (isset($code) && $code == 10) {
                    echo "autofocus";
                } ?> value="<?php if (isset($website) && isset($code) && $code !== 10) {
                    echo $website;
                } else {
                    echo '';
                } ?>"/>
            </div>

            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-user-plus"></i> Crear Proveedor
            </button>
        </form>
    </div>
</section>

<?php
if (isset($code) && $code == 12) {
    ?>
    <section id="container" style="padding: 0">
        <br>
        <h1><i class="fas fa-id-card fa-lg"></i> Lista de Proveedores</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Razon Social</th>
                <th>Tipo de Persona</th>
                <th>RFC</th>
                <th>Calle</th>
                <th>Número</th>
                <th>Colonia</th>
                <th>Código Postal</th>
                <!--<th>ID Localidad</th>  -->
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Página Web</th>
                <th>Acciones</th>
            </tr>

            <?php
            include "conexion.php";
            //paginador
            $por_pagina = 8;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $id_usuario = (int)$_SESSION['id_usuario'];
            $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE estatus=1 ORDER BY id_proveedor DESC LIMIT $desde,$por_pagina");
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $data['id_tipo_persona'] = (int)$data['id_tipo_persona'];
                    $tipo_personaq = mysqli_query($conection, "SELECT tipo_persona FROM tipo_persona WHERE id_tipo_persona = '{$data['id_tipo_persona']}' LIMIT 1");
                    $tipo_personar = mysqli_fetch_assoc($tipo_personaq);
                    $data['tipo_persona'] = $tipo_personar['tipo_persona'];
                    if (empty($data['tipo_persona']))
                        $data['tipo_persona'] = "FISICA";

                    ?>
                    <tr>
                        <td><?php echo $data["id_proveedor"]; ?></td>
                        <td><?php echo $data["razon_social"]; ?></td>
                        <td><?php echo $data['tipo_persona']; ?></td>
                        <td><?php echo $data["rfc"]; ?></td>
                        <td><?php echo $data["calle"]; ?></td>
                        <td><?php echo $data["numero"]; ?></td>
                        <td><?php echo $data["colonia"]; ?></td>
                        <td><?php echo $data["cp"]; ?></td>

                        <td><?php echo $data["telefono"]; ?></td>
                        <td><?php echo $data["mail"]; ?></td>
                        <td><?php echo $data["pagina_web"]; ?></td>
                        <td>
                            <a class="link_edit" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;"
                               href="editar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>"><i
                                        class="fas fa-user-edit"></i> Editar</a>
                            <a class="link_eliminar" style="display:block;padding: 5px 0px 0px 5px;font-size: 11px;"
                               href="eliminar_confirmar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>"><i
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
                <li><a href="lista_proveedores.php" title="Volver al Listado de Proveedores"><i
                                class="fas fa-hand-point-left"></i></a></li>
            </ul>
        </div>

    </section>
<?php } ?>

<?php include "includes/footer.php"; ?>
</body>
</html>