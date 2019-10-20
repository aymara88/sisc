<?php
require_once "includes/verifica_sesion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta lang="es">
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Buscar unidades</title>
</head>
<body>

<?php
include "includes/header.php";
?>
<section id="container">

    <?php
    $busqueda = strtolower($_REQUEST['busqueda']);
    if (empty($busqueda)) {
        header("location: unidades.php");
    }

    ?>

    <br>
    <h1>Unidades</h1>
    <a href="crear_unidad.php" class="btn_new"><i class="fas fa-plus-circle"></i> Nueva unidad</a>

    <form action="buscar_unidad.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $_GET['busqueda']; ?>">
        <button type="submit" class="btn_search"><i class="fas fa-search fa-lg"></i></button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Abreviatura Unidad</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>

        <?php
        include "conexion.php";
        $busqueda = $_GET['busqueda'];
        $search = array("ñ", "Ñ", "á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú");
        $replace = array("n", "N", "a", "A", "e", "E", "i", "I", "o", "O", "u", "U");
        $busqueda = str_replace($search, $replace, $busqueda);
        $busqueda = mysqli_real_escape_string($conection, $busqueda);
        $sql_registrar = mysqli_query($conection, "SELECT COUNT(*) as total_registro 
                                                            FROM unidades 
                                                            WHERE (abreviatura_unidad LIKE '%$busqueda%' or
                                                                   descripcion LIKE '%$busqueda%')
															AND estatus=1
											") or die(mysqli_error());

        $result_register = mysqli_fetch_array($sql_registrar);
        $total_registro = $result_register['total_registro'];
        $por_pagina = 5;

        if (empty($_GET['pagina'])) {
            $pagina = 1;
        } else {
            $pagina = $_GET['pagina'];
        }

        $desde = ($pagina - 1) * $por_pagina;
        $total_paginas = ceil($total_registro / $por_pagina);

        $query = mysqli_query($conection, "SELECT * FROM unidades
                                                    WHERE (abreviatura_unidad LIKE '%$busqueda%' or
                                                           descripcion LIKE '%$busqueda%')
													AND estatus=1
									");
        mysqli_close($conection);
        $result = mysqli_num_rows($query);

        if ($result > 0) {

            while ($data = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $data["id_unidad"]; ?></td>
                    <td><?php echo $data["abreviatura_unidad"]; ?></td>
                    <td><?php echo $data["descripcion"]; ?></td>
                    <td>
                        <a class="link_edit" href="editar_unidad.php?id=<?php echo (int)$data["id_unidad"]; ?>"><i
                                    class="fas fa-user-edit"></i> Editar</a>
                        |
                        <a class="link_eliminar"
                           href="eliminar_confirmar_unidad.php?id=<?php echo (int)$data["id_unidad"]; ?>"><i
                                    class="fas fa-trash"></i> Eliminar</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
    if ($total_registro != 0) {
        ?>
        <div class="paginador">
            <ul>
                <?php
                if ($pagina != 1) {
                    ?>
                    <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
                    <?php
                }
                for ($i = $pagina; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
                    }
                    if ($i >= $pagina + 25)
                        break;
                }
                if ($pagina != $total_paginas) {
                    ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
                    <li><a href="?pagina=<?php echo $total_paginas; ?>&amp;busqueda=<?php echo $busqueda; ?>">>|</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>

</section>
<?php include "includes/footer.php"; ?>
</body>
</html>