<?php 
	require_once('conexion.php');
    require_once('encriptacion.php');
	
	class CrudUsuario{

		public function __construct(){}
        
        //Verifica que el usuario o login sea correcto, es decir que sea mayor a 5 caracteres alfanumericos y menor a 10 caracteres alfanuemricos
        public function esValidoUsuario($username){
		    if (strlen($username) < 5) return false;
		    if (strlen($username) > 10) return false;
		    if (!ctype_alnum($username)) return false;
		     return true;
	    }
        
        //Verifica que la contraseña sea correcta, es decir que sea mayor a 5 caracteres alfanumericos y menor a 12 caracteres alfanumericos
        public function esValidaClave($clave){
		    if (strlen($clave) < 5) return false;
		    if (strlen($clave) > 12) return false;
		    if (!ctype_alnum($clave)) return false;
		     return true;
	    }
        
		//obtiene el nombre del usuario (se refiere al nombre propio del usuario) para el login y clave que se le están pasadon como parámetros y que el estatus del usuario este activo, 
		public function obtenerUsuario($usuario, $clave){
			
            $conn = getConnection();
            $usuario = $usuario;
            $clave = encriptacion::encryption($clave);
            $estatus = 1;
            
			$select=$conn->prepare('SELECT u.id_usuario, u.nombre_usuario, u.login_usuario, r.id_rol, r.descripcion_rol FROM usuarios u INNER JOIN roles r ON r.id_rol = u.id_rol
                                    WHERE u.login_usuario=:usuario AND u.contrasena_usuario=:clave AND u.estatus=:estatus');
                
			$select->bindValue(':usuario',$usuario);
            $select->bindValue(':clave',$clave);
            $select->bindValue(':estatus',$estatus);
			
            try{
                $select->execute();
			    $registro=$select->fetch();
                if($registro){
                    $id_usuario = $registro['id_usuario'];  
                    $nombre_usuario = $registro['nombre_usuario'];
                    $login_usuario = $registro['login_usuario'];
                    $id_rol = $registro['id_rol'];
                    $rol = $registro['descripcion_rol'];
                    $select->closeCursor();  
                    return array($id_usuario,$nombre_usuario,$login_usuario,$id_rol,$rol);
                }else{
                    return array();
                }
            }catch(PDOException $e){
		        echo "En este momento no es posible acceder a SysCProy, intentelo más tarde";
                echo "<br>";
                echo "<a href='../index.php'>Volver a ingresar</a>";
                exit();
	        }  
		} 
      

        //obtiene el login del usuario a traves del correo electrónico, siempre y cuando el usuario este activo, es decir que tenga el valor de 1 en el campo estatus
		public function obtenerDatosUsuario($correo){
			
            $conn = getConnection();
            $email = $correo;
            $estatus = 1;
            
			$select=$conn->prepare('SELECT * FROM usuarios WHERE email=:email AND estatus=:estatus');
			$select->bindValue(':email',$email);
            $select->bindValue(':estatus',$estatus);
			
            try{
                $select->execute();
                $registro=$select->fetch();
                if($registro){
                    $nombre_usuario = $registro['nombre_usuario'];
                    $apellido_paterno = $registro['apellido_pater_usuario'];
                    $login = $registro['login_usuario'];
                    $clave= encriptacion::decryption($registro['contrasena_usuario']);
                    $select->closeCursor(); 
                    return array($nombre_usuario,$apellido_paterno,$login,$clave);
                }else{
                    return array();
                }
            }catch(PDOException $e){
                echo "En este momento no es posible acceder a SysCProy, intentelo más tarde";
                echo "<br>";
                echo "<a href='../index.php'>Volver a ingresar</a>";
                exit();
            }   
                
        }        
	}
?>