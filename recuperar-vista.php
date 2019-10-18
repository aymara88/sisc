<?php
    session_start();

    $error = '';
    if(!empty($_POST)){
        if(empty($_POST['correo'])){
            $error = 'Ingrese su correo electrónico';
        }else{
            require_once "sistema/frontend/conexion.php";
            require_once "sistema/frontend/CrudUsuario.php";
            require 'PHPMailer/PHPMailerAutoload.php';

            $crud = new CrudUsuario();
            $email = $_POST['correo'];
            if((!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i", $email)) && (!filter_var($email,FILTER_VALIDATE_EMAIL)) )  {
                $error = "La dirección de Email no es valida!";
            }else{
                $datosObtenidos = $crud->obtenerDatosUsuario($email);   
                if(empty($datosObtenidos)) {
                    $error = "El Email no se encuentra en nuestra base de datos o no está activo!";
                }else{
                    
                    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
                    $recaptcha_secret = '6Lfa1q8UAAAAACCIWc7nhdvuCuqY6je3iP26Iccu'; 
                    $recaptcha_response = $_POST['recaptcha_response']; 
                    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
                    $recaptcha = json_decode($recaptcha); 
                    
                    if($recaptcha->score >= 0.7){
                        
                        $nombre_completo = $datosObtenidos[0] ." ". $datosObtenidos[1];
                        $mail = new PHPMailer();
                        $mail->isSMTP();
                        $mail->SMTPAuth = true;
                        $mail->CharSet = 'UTF-8';
                        $mail->SMTPSecure = 'tls';
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = '587';


                        $mail->setFrom('ocamcas@gmail.com', 'Constructora OCAMCAS');

                        $mail->addAddress($email, $nombre_completo);
                        $mail->Subject = 'Envío de contraseña de SiscProy';

                        $mail->IsHTML(true);
                        // adjunta files/imagen.jpg o png
                        $mail->AddEmbeddedImage('logo_ocamcas.png','logo','logo_ocamcas.png','base64','image/png');

                        $mail->Body ="<br><b>   C O N S T R U C T O R A   </b><br><br><img src=\"cid:logo\" width=\"350\" height=\"85\" alt=\"Constructora OCAMCAS, Una nueva esperanza S.A de C.V.\" title=\"Constructora OCAMCAS, Una nueva esperanza S.A de C.V.\" /><br><br>Apreciable:<b> $nombre_completo </b>, se envian tus datos de acceso a <b>SiscProy</b><br><br><b>Usuario: </b> $datosObtenidos[2] <br><b>Contraseña:</b> $datosObtenidos[3] <br><br>Puedes ingresar al Sistema Web de Control y gestión de Proyectos de Construcción desde este enlance: <a href=\"https://www.constructoraocamcas.com/siscproy/\">Acceso al sistema SiscProy</a>";

                        $mail->AltBody = "Constructora OCAMCAS, Una nueva esperanza S.A de C.V. Apreciable:$nombre_completo, se envian tus datos de acceso a SiscProy. Usuario:$datosObtenidos[2], Contraseña:$datosObtenidos[3]";

                        if($mail->send()){
                            $error = "Tus datos de acceso se han enviado a tu email, revisa tu bandeja de entrada o correo no deseado";
                        } else {
                            $error = "Hubo un problema con tu email, intentalo más tarde";
                        }
                        // se borran las direcciones de destino establecidas anteriormente
                        $mail->ClearAddresses();
                    }else{
                        // KO. ERES ROBOT, EJECUTA ESTE CÓDIGO
                        $error = 'No eres un humano'; 
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Bienvenido a OCAMCAS Una nueva esperanza</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

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
                    <a href="index.php"><li class="module-login active">Login</li></a>
                    <a href=""><li class="module-register">Recuperar contraseña</li></a>
                </div>
            </div>

            <form action="" method="post" class="form">
                <div class="welcome-form"><h1>Bienvenido a</h1><h2>OCAMCAS</h2></div>
                    <div class="user line-input">
                        <label class="lnr lnr-envelope"></label>
                        <input type="email" placeholder=" Introduce tu Correo electrónico" name="correo" required pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}">
                    </div>
                    <div>
                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse"> 
                    </div>

                 <?php if(!empty($error)): ?>
                <div class="mensaje">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <button type="submit">Recuperar<label class="lnr lnr-chevron-right"></label></button>
            </form>
        </div>
       <script src="sistema/frontend/js/script.js"></script>

        
        <?php  include "sistema/frontend/includes/footer.php"; ?>

    </body>
</html>