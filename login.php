<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
        $error = 'Su sesion fué cerrada después de 15 minutos de inactividad, vuelva a iniciar sesión';
        if(!empty($_POST)){
            if(empty($_POST['usuario']) || empty($_POST['clave'])){
                $error = 'Ingrese su usuario y contraseña';
            }else{
                require_once "sistema/frontend/conexion.php";
                require_once "sistema/frontend/CrudUsuario.php";

                $crud = new CrudUsuario();

                $usuario = $_POST['usuario'];
                $clave = $_POST['clave'];
                $enable_recaptcha = false;
				
                if($crud->esValidoUsuario($usuario) and $crud->esValidaClave($clave)){
                     
                    $datos_user = $crud->obtenerUsuario($usuario,$clave);
                    if(empty($datos_user)){  
                        $error = 'El usuario o la contraseña son incorrectos, o la cuenta del usuario proporcionada ya NO está disponible'; 
                    }else if($enable_recaptcha == true && !empty($datos_user)){
                        
                        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
                        $recaptcha_secret = '6Lfa1q8UAAAAACCIWc7nhdvuCuqY6je3iP26Iccu'; 
                        $recaptcha_response = $_POST['recaptcha_response']; 
                        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
                        $recaptcha = json_decode($recaptcha); 
                        
                       // echo $recaptcha->score;

                        if($recaptcha->score >= 0.7){
                            // OK. ERES HUMANO, EJECUTA ESTE CÓDIGO
                            $_SESSION['id_usuario'] = $datos_user[0];
                            $_SESSION['nombre_usuario'] = $datos_user[1];        
                            $_SESSION['login_usuario'] = $datos_user[2];
                            $_SESSION['id_rol'] = $datos_user[3]; 
                            $_SESSION['rol'] = $datos_user[4];
                            $_SESSION['start'] = time();
                            $_SESSION['expire'] = $_SESSION['start'] + (60 * 15) ;   
                            header('location: sistema/index.php');
                        }else{
                            // KO. ERES ROBOT, EJECUTA ESTE CÓDIGO
                            $error = 'No eres un humano'; 
                        } 
                    }
					else if($enable_recaptcha == false){
						$_SESSION['id_usuario'] = $datos_user[0];
                        $_SESSION['nombre_usuario'] = $datos_user[1];        
                        $_SESSION['login_usuario'] = $datos_user[2];
                        $_SESSION['id_rol'] = $datos_user[3]; 
                        $_SESSION['rol'] = $datos_user[4];
                        $_SESSION['start'] = time();
                        $_SESSION['expire'] = $_SESSION['start'] + (60 * 15) ;
                        header('location: sistema/index.php');
					}
                }else{
                    $error = 'El nombre de usuario debe ser alfanumérico y tener entre 5 y 10 caracteres y la contraseña entre 5 y 12 caracteres';
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Bienvenido a OCAMCAS Una nueva esperanza</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- Archivos de estilos css -->
        <link rel="stylesheet" href="sistema/frontend/css/estilos_index.css">
        <link rel="stylesheet" href="sistema/frontend/css/flexslider.css" type="text/css">
        <link rel="stylesheet" href="sistema/frontend/icon/style.css">

        <!-- Archivos jQuery-->
        <script src="sistema/frontend/js/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="sistema/frontend/js/jquery.js"></script>
        

        <!-- Archivos para el slider -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="sistema/frontend/js/jquery.flexslider.js"></script>


        <!-- archivo de fuentes -->
        <link href="//fonts.googleapis.com/css?family=Text+Me+One" rel="stylesheet" type="text/css">

        <script type="text/javascript" charset="utf-8">
            $(window).load(function() {
                $('.flexslider').flexslider();
            });
        </script>
        
        <script src='https://www.google.com/recaptcha/api.js?render=6Lfa1q8UAAAAAKpdZv83zVZQQHE2nJW-8ghSsHx5'> </script>
        <script>
            grecaptcha.ready(function() {
            grecaptcha.execute('6Lfa1q8UAAAAAKpdZv83zVZQQHE2nJW-8ghSsHx5', {action: 'Login'})
            .then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
            });});
        </script>     
                  
                                                  
    </head>
    <body>
        <?php  include "sistema/frontend/includes/header.php"; ?>
    
        <div class="container-form">
            <div class="header">
                <div class="logo-title">
                   <img src="sistema/frontend/imagenes/logo_ocamcas2.png" alt="">
                </div>
                <div class="menu">
                    <a href=""><li class="module-login active">Login</li></a>
                    <a href="recuperar-vista.php"><li class="module-register">Recuperar contraseña</li></a>
                </div>
            </div>

            <form action="" method="post" class="form">
                <div class="welcome-form"><h1>Bienvenido a</h1><h2>OCAMCAS</h2></div>
                <div class="user line-input">
                    <label class="lnr lnr-user"></label>
                    <input type="text" placeholder="Nombre de Usuario" name="usuario" maxlength="10" required pattern="[A-Za-z0-9]{5,10}" title="Introduce Letras y números. Tamaño mínimo: 5. Tamaño máximo: 10">
                </div>
                <div class="password line-input">
                    <label class="lnr lnr-lock"></label>
                    <input type="password" placeholder="Contraseña" name="clave" maxlength="12" required pattern="[A-Za-z0-9]{5,12}" title="Introduce Letras y números. Tamaño mínimo: 5. Tamaño máximo: 12">
                </div>
                 <div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse"> 
                 </div>

                 <?php if(!empty($error)): ?>
                <div class="mensaje">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <button type="submit">Entrar<label class="lnr lnr-chevron-right"></label></button>
            </form>
        </div>
        <script src="sistema/frontend/js/script.js"></script>

        <?php  include "sistema/frontend/includes/footer.php"; ?>

    </body>
</html>