<?php   
include "../conexion.php";

function inserta_usuario($nombre,$apaterno,$amaterno,$sexo,$cargo,$telefono,$email,$login_user,$clave,$rol){
    
    $stmt = $conection->prepare("INSERT INTO clientes (nombre_cliente,id_tipo_persona,rfc_cliente,id_localidad,colonia,calle,numero,cp,telefono,email,id_usuario) 
             VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
             
    $stmt->bind_param("sisissssssi", $nombre, $tipo_persona, $rfc, $id_localidad, $colonia, $calle, $numero, $cp, $telefono, $email, $id_usuario);
    
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

?>