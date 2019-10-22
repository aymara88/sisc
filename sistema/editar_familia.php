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
    $familia = strtoupper($_POST['familia']);
    $descripcion = strtoupper($_POST['descripcion']);
    $id_familia = (int)$_POST['id_familia'];

    if (empty($familia)) {
        $alert = "Introduce el nombre de la nueva familia!";
        $code = 1;
    } else if (empty($descripcion)) {
        $descripcion = "Debe llevar una descripción";
        $code = 2;
    } else {
        $familia = mysqli_real_escape_string($conection, $familia);
        $descripcion = mysqli_real_escape_string($conection, $descripcion);
        $id_familia = mysqli_real_escape_string($conection, $id_familia);

        $query_update = mysqli_query($conection, "UPDATE familias SET familia='$familia', descripcion='$descripcion' WHERE id_familia='$id_familia'");
        if ($query_update) {
            $alert = "Familia editada correctamente!";
            $code = 4;
        } else {
            $alert = "Error al crear familia!";
            $code = 5;
        }

    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Editar Familia</title>
</head>
<body>

<?php
include "includes/header.php";
$id = (int)$_GET['id'];
$query_fam = mysqli_query($conection, "SELECT * FROM familias WHERE id_familia='$id' LIMIT 1") or die ("No existe la familia seleccionada");
$result = mysqli_fetch_array($query_fam);
if ($result > 0) {
    $id_familia = (int)$result['id_familia'];
    $familia = htmlspecialchars($result['familia']);
    $descripcion = htmlspecialchars($result['descripcion']);
    ?>
    <section id="container">
        <div class="form_register">
            <br>
            <h1><i class="fas fa-toolbox fa-lg"></i> Editar familia</h1>
            <hr>
            <?php if (isset($alert)) { ?>
                <div class="alert"><?php echo $alert; ?>
                </div>
                <?php
            }
            ?>
            <form action="" method="post" name="miForm">
                <div class="divisor_resp">
                    <label for="familia">Familia</label>
                    <input type="text" name="familia" id="familia" value="<?php echo $familia ?>" maxlength="50"
                           required pattern="[A-Za-z ñÑ À-ú]{5,50}"
                           title="Introduzca sólo letras. Tamaño mínimo: 5. Tamaño máximo: 50"
                           onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 1) {
                        echo "autofocus";
                    } ?> />
                </div>
                <div class="divisor_resp">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion ?>"
                           maxlength="80" required pattern="[A-Za-z ñÑ À-ú]{5,80}"
                           title="Introduzca sólo letras. Tamaño mínimo: 5. Tamaño máximo: 80"
                           onchange="javascript:this.value=this.value.toUpperCase();" <?php if (isset($code) && $code == 2) {
                        echo "autofocus";
                    } ?> />
                </div>
                <div class="divisor_resp"></div>
                <input type="hidden" name="id_familia" id="id_familia" value="<?php echo $id_familia ?>"/>
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