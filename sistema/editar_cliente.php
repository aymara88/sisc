<?php
require_once "includes/verifica_sesion.php";
include "conexion.php";
if (isset($_POST['btn-signup'])) {
    $alert = '';
    $id_cliente = $_POST['id'];
    $nombre = strtoupper($_POST['nombre']);
    $tipo_persona = $_POST['tipo_persona'];
    $rfc = trim(strtoupper($_POST['rfc_cliente']));
    $id_estado = $_POST['cbx_estado'];
    $id_municipio = $_POST['cbx_municipio'];
    $id_localidad = $_POST['cbx_localidad'];
    $colonia = strtoupper($_POST['colonia']);
    $calle = strtoupper($_POST['calle']);
    $numero = trim($_POST['numero']);
    $cp = trim($_POST['cp']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['correo']);
    $id_usuario = $_SESSION['id_usuario'];
    $resultado_rfc = strtoupper($_POST['resultado_rfc']);

    //se comprueba que los campos no esten vacios y cumplan con los datoa que deben recibir
    if ($tipo_persona == 1) {
        if (empty($nombre)) {
            $alert = "Introduce tu nombre!";
            $code = 2;
        } else if (!preg_match("/^[a-zA-ZÀ-ÿÑñ ]+(\s*[a-zA-ZÀ-ÿÑñ ]*)*[a-zA-ZÀ-ÿÑñ ]+$/i", $nombre)) {
            $alert = "Introduce correctamente tu nombre!";
            $code = 2;
        }
    } else {
        if (empty($nombre)) {
            $alert = "Introduce tu Razón Social!";
            $code = 2;
        }
    }
    if (empty($rfc)) {
        $alert = "Introduce tu RFC!";
        $code = 1;
    } else if ($resultado_rfc == 0) { //aca estamos comprobando a traves del hidden input el resultado de la funcion javascript que valida el RFC
        $alert = "El RFC introducido no es valido";
        $code = 1;
    } else if (empty($id_estado)) {
        $alert = "Selecciona un Estado!";
        $code = 3;
    } else if ($id_estado == 0) {
        $alert = "Selecciona un Estado!";
        $code = 3;
    } else if (empty($id_municipio)) {
        $alert = "Selecciona un Municipio!";
        $code = 4;
    } else if ($id_municipio == 0) {
        $alert = "Seleciona un Municipio!";
        $code = 4;
    } else if (empty($id_localidad)) {
        $alert = "Selecciona la Localidad!";
        $code = 5;
    } else if ($id_localidad == 0) {
        $alert = "Seleciona la Localidad!";
        $code = 5;
    } else if (empty($colonia)) {
        $alert = "Introduce el nombre de la Colonia!";
        $code = 6;
    } else if (empty($calle)) {
        $alert = "Introduce el nombre de la Calle!";
        $code = 7;
    } else if (empty($numero)) {
        $alert = "Introduce el número!";
        $code = 8;
    } else if (empty($cp)) {
        $alert = "Introduce el CP!";
        $code = 9;
    } else if (empty($telefono)) {
        $alert = "Introduce tu número de teléfono!";
        $code = 10;
    } else if (!is_numeric($telefono)) {
        $alert = "Introduce sólo números!";
        $code = 10;
    } else if (strlen($telefono) != 10) {
        $alert = "Deben ser 10 números !";
        $code = 10;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "Introduce una direción de correo electrónico valida!";
        $code = 11;
    } /*else if (!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i", $email)) {
        $alert = "La dirección de Email no es valida!";
        $code = 11;
    } */ else {
        $id_cliente = mysqli_real_escape_string($conection, $id_cliente);
        $nombre = mysqli_real_escape_string($conection, $nombre);
        $tipo_persona = mysqli_real_escape_string($conection, $tipo_persona);
        $rfc = mysqli_real_escape_string($conection, $rfc);
        $id_localidad = mysqli_real_escape_string($conection, $id_localidad);
        $colonia = mysqli_real_escape_string($conection, $colonia);
        $calle = mysqli_real_escape_string($conection, $calle);
        $numero = mysqli_real_escape_string($conection, $numero);
        $cp = mysqli_real_escape_string($conection, $cp);
        $telefono = mysqli_real_escape_string($conection, $telefono);
        $email = mysqli_real_escape_string($conection, $email);
        $id_usuario = mysqli_real_escape_string($conection, $id_usuario);
        $query = mysqli_query($conection, "SELECT * FROM clientes WHERE (rfc_cliente = '$rfc' AND id_cliente != $id_cliente) ");
        $result = mysqli_fetch_array($query);
        //$result = count($result);
        if ($result > 0) {
            $alert = '<p class="msg_error"> El RFC ya existe y corresponde a otro Cliente.</div>';
            $code = 12;
        } else {
            $sql_update = mysqli_query($conection, "UPDATE clientes SET nombre_cliente = '$nombre', id_tipo_persona = $tipo_persona, rfc_cliente = '$rfc', id_localidad = $id_localidad, colonia= '$colonia', calle = '$calle', numero = '$numero', cp = '$cp', telefono = '$telefono', email = '$email' WHERE id_cliente = $id_cliente ");
            if ($sql_update) {
                $alert = '<p class="msg_save"> Datos del Cliente actualizados correctamente.</div>';
                $code = 13;
            } else {
                $alert = '<p class="msg_error"> Error al actualizar los datos del Cliente.</div>';
                $code = 14;
            }
        }
    }
}

/* mostar datos */

if (empty($_REQUEST['id'])) {
    header('Location: lista_clientes.php');
    mysqli_close($conection);
}

$id_cliente = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT c.id_cliente, c.nombre_cliente, t.id_tipo_persona, t.tipo_persona, c.rfc_cliente, e.id_estado, e.nombre_estado, m.id_municipio, m.nombre_municipio, l.id_localidad, l.nombre_localidad, c.colonia, c.calle, c.numero, c.cp, c.telefono, c.email 
          FROM clientes c INNER JOIN tipo_persona t ON t.id_tipo_persona = c.id_tipo_persona
                          INNER JOIN localidades l ON l.id_localidad = c.id_localidad
                          INNER JOIN municipios m ON m.id_municipio = l.id_municipio
                          INNER JOIN estados e ON e.id_estado = m.id_estado
          WHERE c.id_cliente = $id_cliente");

$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header('Location: lista_clientes.php');
} else {

    $option_persona = '';
    while ($data = mysqli_fetch_array($sql)) {
        $id_cliente = $data["id_cliente"];
        $nombre_cliente = $data["nombre_cliente"];
        $id_tipo_persona = $data["id_tipo_persona"];
        $tipo_persona = $data["tipo_persona"];
        $rfc_cliente = $data["rfc_cliente"];
        $id_estado = $data["id_estado"];
        $nombre_estado = $data["nombre_estado"];
        $id_municipio = $data["id_municipio"];
        $nombre_municipio = $data["nombre_municipio"];
        $id_localidad = $data["id_localidad"];
        $nombre_localidad = $data["nombre_localidad"];
        $colonia = $data["colonia"];
        $calle = $data["calle"];
        $numero = $data["numero"];
        $cp = $data["cp"];
        $telefono = $data["telefono"];
        $email = $data["email"];
        $select_tipo_persona = "";
        $query_tipo_persona = mysqli_query($conection, "SELECT * FROM tipo_persona");
        $result_tipo_persona = mysqli_num_rows($query_tipo_persona);
        if ($result_tipo_persona > 0) {
            while ($tipo_persona_row = mysqli_fetch_array($query_tipo_persona)) {
                if ($id_tipo_persona == $tipo_persona_row["id_tipo_persona"]) $selectedp = " selected=\"selected\""; else                        $selectedp = "";
                $select_tipo_persona .= "<option value=\"{$tipo_persona_row["id_tipo_persona"]}\"{$selectedp}>{$tipo_persona_row["tipo_persona"]}</option>";
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
    <title>Actualizar Clientes</title>
    <script language="javascript" src="js/jquery-3.1.1.min.js"></script>
    <script language="javascript">
        $(document).ready(function () {
            $("#cbx_estado").change(function () {
                $('#cbx_localidad').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                $("#cbx_estado option:selected").each(function () {
                    id_estado = $(this).val();
                    $.post("includes/getMunicipio.php", {id_estado: id_estado}, function (data) {
                        $("#cbx_municipio").html(data);
                    });
                });
            })
        });

        $(document).ready(function () {
            $("#cbx_municipio").change(function () {
                $("#cbx_municipio option:selected").each(function () {
                    id_municipio = $(this).val();
                    $.post("includes/getLocalidad.php", {id_municipio: id_municipio}, function (data) {
                        $("#cbx_localidad").html(data);
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

<?php include "includes/header.php"; ?>
<section id="container">
    <div class="form_register">
        <br>
        <h1>Actualizar clientes</h1>
        <hr>
        <?php if (isset($alert)) { ?>
            <div class="alert"><?php echo $alert; ?>
            </div>
            <?php
        }
        ?>
        <form action="" method="post" id="combo" name="combo">
            <div class="divisor_resp">
                <input type="hidden" name="id" value="<?php echo $id_cliente; ?>"/>
                <label for="tipo_persona">Tipo persona</label>
                <select name="tipo_persona" id="tipo_persona">
                    <?php
                    echo $select_tipo_persona;
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc_cliente" id="rfc_cliente" value="<?php echo $rfc_cliente; ?>"
                       maxlength="13" required pattern="[0-9A-Za-z-9À-ÿ\u00f1\u00d1 ]{13,13}"
                       title="Introduzca su RFC. Tamaño mínimo: 13. Tamaño máximo: 13" autofocus
                       onchange="javascript:this.value=this.value.toUpperCase();"
                       oninput="validarInput(this)" <?php if (isset($code) && $code == 1) {
                    echo "autofocus";
                } ?> />
                <label id="resultado"></label>
                <input type="hidden" name="resultado_rfc" id="resultado_rfc" value="1"/>
            </div>

            <div class="divisor_resp">
                <label for="nombre">Nombre o razon social</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $nombre_cliente; ?>" maxlength="50"
                       required pattern="[A-Za-z ]{10,60}"
                       title="Introduzca sólo letras. Tamaño mínimo: 10. Tamaño máximo: 60"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="estado">Estado</label>
                <?php
                $query_estados = mysqli_query($conection, "SELECT id_estado, nombre_estado FROM estados ORDER BY nombre_estado");
                $resultado_estados = mysqli_num_rows($query_estados);
                ?>
                <select name="cbx_estado" id="cbx_estado" required>
                    <option value="<?php echo $id_estado; ?>"><?php echo $nombre_estado; ?></option>
                    <?php
                    if ($resultado_estados > 0) {
                        while ($estados = mysqli_fetch_array($query_estados)) {
                            ?>
                            <option value="<?php echo $estados["id_estado"]; ?>"><?php echo $estados['nombre_estado']; ?> </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="municipio">Municipio</label>
                <select name="cbx_municipio" id="cbx_municipio" required>
                    <option value="<?php echo $id_municipio; ?>"><?php echo $nombre_municipio; ?></option>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="localidad">Localidad</label>
                <select name="cbx_localidad" id="cbx_localidad" required>
                    <option value="<?php echo $id_localidad; ?>"><?php echo $nombre_localidad; ?></option>
                </select>
            </div>

            <div class="divisor_resp">
                <label for="colonia">Nombre de la Colonia</label>
                <input type="text" name="colonia" id="colonia" value="<?php echo $colonia; ?>" maxlength="50" required
                       pattern="[A-Za-z ]+{5,50}"
                       title="Introduzce el nombre de la Colonia. Tamaño mínimo: 5. Tamaño máximo: 50"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 3) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="calle">Nombre de la Calle</label>
                <input type="text" name="calle" id="calle" value="<?php echo $calle; ?>" maxlength="50" required
                       pattern="[A-Za-z ]+{5,50}"
                       title="Introduzce el nombre de la Calle. Tamaño mínimo: 5. Tamaño máximo: 50"
                       onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 4) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" value="<?php echo $numero; ?>" maxlength="5" required
                       pattern="[0-9]{1,4}"
                       title="Introduzca el número. Tamaño mínimo: 1. Tamaño máximo: 4" <?php if (isset($code) && $code == 5) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="cp">Código postal</label>
                <input type="text" name="cp" id="cp" value="<?php echo $cp; ?>" maxlength="5" required
                       pattern="[0-9]{1,5}"
                       title="Introduzca el CP. Tamaño mínimo: 1. Tamaño máximo: 5" <?php if (isset($code) && $code == 6) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="telefono">Número de Teléfono</label>
                <input type="tel" name="telefono" id="telefono" value="<?php echo $telefono; ?>" maxlength="10" required
                       pattern="[0-9]{10,10}"
                       title="Introduzca sólo números. Tamaño mínimo: 10. Tamaño máximo: 10" <?php if (isset($code) && $code == 7) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp">
                <label for="correo">Correo Electronico</label>
                <input type="text" name="correo" id="correo" value="<?php echo $email; ?>"
                       required
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       title="Ejemplo: dina@gmail.com" <?php if (isset($code) && $code == 8) {
                    echo "autofocus";
                } ?> />
            </div>

            <div class="divisor_resp"></div>
            <div class="divisor_resp">
                <input type="submit" id="enviar" name="btn-signup" value="Actualizar datos del Cliente"
                       class="btn_save">
            </div>
        </form>


    </div>

</section>
<?php include "includes/footer.php"; ?>
</body>
</html>