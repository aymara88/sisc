<?php
require_once "includes/verifica_sesion.php";
include "conexion.php";
include "frontend/encriptacion.php";
require_once('frontend/CrudUsuario.php');
if (isset($_POST['btn-signup'])) {
    $alert = '';
    $id_proveedor = (int)$_POST['id_proveedor'];
    $razon_social = strtoupper($_POST['razon_social']);
    $rfc = strtoupper($_POST['rfc']);
    $calle = strtoupper($_POST['calle']);
    $numero = intval($_POST['numero']);
    $colonia = strtoupper($_POST['colonia']);
    $codigo_postal = intval($_POST['codigo_postal']);
    $id_localidad = $_POST['localidad'];
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $website = trim($_POST['website']);
    $tipo_persona = $_POST['tipo_persona'];
    $resultado_rfc = strtoupper($_POST['resultado_rfc']);

    if ($tipo_persona == 1) {
        if (empty($razon_social)) {
            $alert = "Introduce tu nombre!";
            $code = 1;
        } else if (!preg_match("/^[a-zA-ZÀ-ÿñÑ]+(\s*[a-zA-ZÀ-ÿñÑ]*)*[a-zA-ZÀ-ÿñÑ]+$/i", $razon_social)) {
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
        $alert = "Introduce el Registro Federal del contribuyente de tu proveedor!";
        $code = 2;
    } else if (!preg_match("/^[0-9a-zA-ZÀ-ÿñÑ ]+$/i", $rfc)) {
        $alert = "Introduce sólo letras o números para el RFC!";
        $code = 2;
    } else if ($resultado_rfc == 0) { //aca estamos comprobando a traves del hidden input el resultado de la funcion javascript que valida el RFC
        $alert = "El RFC introducido no es valido";
        $code = 2;
    } else if (empty($calle)) {
        $alert = "Introduce la calle del proveedor!";
        $code = 3;
    } else if (!preg_match("/^[0-9a-zA-ZÀ-ÿñÑ ]+$/i", $calle)) {
        $alert = "Introduce sólo letras o números en la calle del proveedor!";
        $code = 3;
    } else if (empty($numero)) {
        $alert = "Introduce el número de la vivienda del proveedor!";
        $code = 4;
    } else if (!preg_match("/^[0-9]+$/i", $numero)) {
        $alert = "Introduce sólo números para el número de vivienda del proveedor!";
        $code = 4;
    } else if (empty($colonia)) {
        $alert = "Introduce la colonia del proveedor!";
        $code = 5;
    } else if (!preg_match("/^[0-9a-zA-ZÀ-ÿ ]+$/i", $colonia)) {
        $alert = "Introduce sólo letras o números para la colonia!";
        $code = 5;
    } else if (empty($codigo_postal)) {
        $alert = "Introduce el código postal!";
        $code = 6;
    } else if (!preg_match("/^[0-9]+$/i", $codigo_postal)) {
        $alert = "Introduce sólo números para el código postal!";
        $code = 6;
    } else if (empty($id_localidad || $id_localidad == 0)) {
        $alert = "Localidad incorrecta!";
        $code = 7;
    } else if (!preg_match("/^[0-9]+$/i", $id_localidad)) {
        $alert = "Introduce sólo números para la id de la localidad!";
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
    } else if (!is_numeric($tipo_persona)) {
        $alert = "Debe elegir un tipo de persona válido!";
        $code = 14;
    } else {
        $id_proveedor = mysqli_real_escape_string($conection, $id_proveedor);
        $razon_social = mysqli_real_escape_string($conection, $razon_social);
        $rfc = mysqli_real_escape_string($conection, $rfc);
        $calle = mysqli_real_escape_string($conection, $calle);
        $numero = mysqli_real_escape_string($conection, $numero);
        $colonia = mysqli_real_escape_string($conection, $colonia);
        $codigo_postal = mysqli_real_escape_string($conection, $codigo_postal);
        $id_localidad = mysqli_real_escape_string($conection, $id_localidad);
        $tipo_persona = mysqli_real_escape_string($conection, $tipo_persona);
        $telefono = mysqli_real_escape_string($conection, $telefono);
        $email = mysqli_real_escape_string($conection, $email);
        $website = mysqli_real_escape_string($conection, $website);
        $query_update = mysqli_query($conection, "UPDATE proveedores SET razon_social='$razon_social', rfc='$rfc', calle='$calle', numero='$numero', colonia='$colonia', cp='$codigo_postal', id_localidad='$id_localidad', telefono='$telefono', mail='$email', pagina_web='$website', id_tipo_persona='$tipo_persona' WHERE id_proveedor='{$id_proveedor}'");
        if ($query_update) {
            $alert = "Proveedor editado correctamente!";
            $code = 12;
        } else {
            $alert = "Error al editar proveedor!";
            $code = 13;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Edición de Proveedores</title>
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
        <br>
        <h1><i class="fas fa-user-friends fa-lg"></i> Editar proveedor</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        $user_id = (int)$_GET['id'];
        $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE id_proveedor='{$user_id}' LIMIT 1");
        $result = mysqli_num_rows($query);
        if ($result > 0)        {
        while ($data = mysqli_fetch_array($query))            {
        $data["id_proveedor"] = (int)$data["id_proveedor"];
        $data["razon_social"] = htmlspecialchars($data["razon_social"]);
        $data["rfc"] = htmlspecialchars($data["rfc"]);
        $data["calle"] = htmlspecialchars($data["calle"]);
        $data["numero"] = (int)$data["numero"];
        $data["colonia"] = htmlspecialchars($data["colonia"]);
        $data["codigo_postal"] = (int)$data["cp"];
        $data["id_localidad"] = $data["id_localidad"];
        $data["telefono"] = (int)$data["telefono"];
        $data["email"] = htmlspecialchars($data["mail"]);
        $data["pagina_web"] = htmlspecialchars($data["pagina_web"]);
        $select1 = $select2 = $select3 = $select_tipo_persona = "";
        $query_tipo_persona = mysqli_query($conection, "SELECT * FROM tipo_persona");
        $result_tipo_persona = mysqli_num_rows($query_tipo_persona);
        if ($result_tipo_persona > 0) {
            while ($tipo_persona_row = mysqli_fetch_array($query_tipo_persona)) {
                if ($data['id_tipo_persona'] == $tipo_persona_row["id_tipo_persona"]) $selectedp = " selected=\"selected\""; else                            $selectedp = "";
                $select_tipo_persona .= "<option value=\"{$tipo_persona_row["id_tipo_persona"]}\"{$selectedp}>{$tipo_persona_row["tipo_persona"]}</option>";
            }
        }
        $query_localidad = mysqli_query($conection, "SELECT * FROM localidades WHERE id_localidad={$data["id_localidad"]} ORDER BY nombre_localidad");
        $resultado_localidad = mysqli_num_rows($query_localidad);
        if ($resultado_localidad > 0) {
            while ($localidad = mysqli_fetch_array($query_localidad)) {
                $value1 = (int)$localidad['id_localidad'];
                $value_comp = (int)$data["id_localidad"];
                if ($value1 == $value_comp) {
                    $municipio = (int)$localidad['id_municipio'];
                    $selected = " selected=\"selected\"";
                } else {
                    $selected = "";
                }
                $option1 = htmlspecialchars($localidad['nombre_localidad']);
                $select1 .= "<option value=\"$value1\"$selected>$option1</option>";
            }
        }
        if (isset($municipio)) {
            $query_municipio = mysqli_query($conection, "SELECT * FROM municipios ORDER BY nombre_municipio");
            $resultado_municipio = mysqli_num_rows($query_municipio);

            if ($resultado_municipio > 0) {
                while ($municipior = mysqli_fetch_array($query_municipio)) {
                    $value2 = (int)$municipior['id_municipio'];
                    $value_comp2 = (int)$municipio;
                    if ($value2 == $value_comp2) {
                        $estado = (int)$municipior['id_estado'];
                        $selected2 = " selected=\"selected\"";
                    } else {
                        $selected2 = "";
                    }
                    $option2 = htmlspecialchars($municipior['nombre_municipio']);
                    $select2 .= "<option value=\"$value2\"$selected2>$option2</option>";
                }
            }
        }
        if (isset($estado)) {
            $query_estado = mysqli_query($conection, "SELECT id_estado, nombre_estado FROM estados ORDER BY nombre_estado");
            $resultado_estado = mysqli_num_rows($query_estado);
            if ($resultado_estado > 0) {
                while ($estador = mysqli_fetch_array($query_estado)) {
                    $value3 = (int)$estador['id_estado'];
                    $value_comp3 = (int)$estado;
                    if ($value3 == $value_comp3) {
                        $selected3 = " selected=\"selected\"";
                    } else {
                        $selected3 = "";
                    }
                    $option3 = htmlspecialchars($estador['nombre_estado']);
                    $select3 .= "<option value=\"$value3\"$selected3>$option3</option>";
                }
            }
        }

        ?>
        <form action="" method="post" name="miForm">

            <div class="divisor_resp">
                <label for="tipo_persona">Tipo persona</label>
                <select name="tipo_persona" id="tipo_persona">
                    <?php echo $select_tipo_persona; ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" id="rfc" maxlength="13" required
                       pattern="[0-9A-Za-z-9À-ÿ\u00f1\u00d1 ]{12,13}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 12. Tamaño máximo: 13"
                       value="<?php echo $data["rfc"] ?>" onchange="javascript:this.value=this.value.toUpperCase();"
                       oninput="validarInput(this)" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> />
                <label id="resultado"></label>
                <input type="hidden" name="resultado_rfc" id="resultado_rfc" value="1"/>
            </div>

            <div class="divisor_resp">
                <label for="razon_social">Razón Social</label>
                <input type="text" name="razon_social" id="razon_social" maxlength="120" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{10,120}"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 10. Tamaño máximo: 120"
                       value="<?php echo $data["razon_social"] ?>" autofocus
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="calle">Calle</label>
                <input type="text" name="calle" id="calle" maxlength="70" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{2,70}"
                       onchange="javascript:this.value=this.value.toUpperCase();"
                       title="Introduzca sólo letras o números. Tamaño mínimo: 2. Tamaño máximo: 70"
                       value="<?php echo $data["calle"] ?>" <?php if (isset($code) && $code == 3) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" maxlength="15" required
                       pattern="[0-9]{1,4}"
                       title="Introduzca sólo números. Tamaño mínimo: 1. Tamaño máximo: 4"
                       value="<?php echo $data["numero"] ?>" <?php if (isset($code) && $code == 4) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="colonia">Colonia</label>
                <input type="text" name="colonia" id="colonia" maxlength="60" required
                       pattern="[0-9A-Za-zÀ-ÿ\u00f1\u00d1 ]{5,60}"
                       title="Introduzca el nombre de la Colonia. Tamaño mínimo: 5. Tamaño máximo: 60"
                       onchange="javascript:this.value=this.value.toUpperCase();"
                       title="Introduzca sólo letras o números. Tamaño máximo: 60"
                       value="<?php echo $data["colonia"] ?>" <?php if (isset($code) && $code == 5) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" name="codigo_postal" id="codigo_postal" maxlength="5" required pattern="[0-9 ]{1,5}"
                       title="Introduzca sólo números. Tamaño mínimo: 1. Tamaño máximo: 5"
                       value="<?php echo $data["codigo_postal"] ?>" <?php if (isset($code) && $code == 6) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required>
                    <?php
                    echo $select3;
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="municipio">Municipio</label>
                <select name="municipio" id="municipio" required>
                    <?php
                    echo $select2;
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="localidad">Localidad</label>
                <select name="localidad" id="localidad" required>
                    <?php
                    echo $select1;
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" maxlength="10" required pattern="[0-9 ]{10,10}"
                       title="Introduzca sólo números. Tamaño: 10 dígitos"
                       value="<?php echo $data["telefono"] ?>" <?php if (isset($code) && $code == 8) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="email">Correo Electrónico</label>
                <input type="text" name="email" id="email" maxlength="70"
                       required
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       title="Ejemplo: dina@gmail.com"
                       value="<?php echo $data["email"] ?>" <?php if (isset($code) && $code == 9) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="website">Página Web</label>
                <input type="text" name="website" id="website" maxlength="120" pattern="[A-Za-z0-9!?-.]{0,120}"
                       title="Introduzca su Página Web. Tamaño máximo: 120"
                       value="<?php echo $data["pagina_web"] ?>" <?php if (isset($code) && $code == 10) {
                    echo "autofocus";
                } ?> />
            </div>

            <input type="hidden" value="<?php echo $data["id_proveedor"] ?>" name="id_proveedor" id="id_proveedor"/>
            <button type="submit" name="btn-signup" class="btn_save"><i class="fas fa-user-plus"></i> Guardar Cambios
            </button>
        </form>
    </div>

</section>
<?php
}
}
mysqli_close($conection);
include "includes/footer.php";
?>
</body>
</html>