<?php
class UsuarioModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function registrar($nombre,$apellido,$fecha_nac,$genero,$ubicacion,$email,$user_name,$hash,$ruta_imagen,$token,$pais){
        $query = $this->database->query(
            "INSERT INTO usuario (nombre,apellido,fecha_nac,genero,ubicacion,email,user_name,contrasenia,foto_perfil, token, pais)
             VALUES ('$nombre','$apellido','$fecha_nac','$genero','$ubicacion','$email','$user_name','$hash','$ruta_imagen', '$token', '$pais')"
        );
    }

    public function validarUsername($username){
        $query = $this->database->query(
            "SELECT user_name
            FROM usuario
            WHERE user_name = '$username'"
        );

        return $query->num_rows === 0;
    }

    public function verificarCredenciales($usuario,$contrasenia){

        $sql = "SELECT id_usuario,contrasenia 
                FROM usuario
                WHERE user_name = '$usuario'";

        $query = $this->database->query($sql);

        $fila = $query->fetch_assoc();

        $contraseniaHasheada = $fila["contrasenia"];

        return password_verify($contrasenia,$contraseniaHasheada);
    }

    public function validarEmail($email){
        return filter_var($email,FILTER_VALIDATE_EMAIL);
    }

    public function hashearClave($clave){
        return password_hash($clave, PASSWORD_DEFAULT);
    }

    public function validarImagen($imagen_nombre,$user_name){
        $targetDir = "public/foto_perfil/";
        $targetFile = $targetDir . $user_name. basename($imagen_nombre);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $new_file_name = $targetDir .$user_name. $imagen_nombre ;
        $validExtensions = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $validExtensions)){
            if(move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $targetFile)){
                return $new_file_name;
            }
        }
    }

    public function actualizarNombreImg($nombre,$imgFileTye){
        $nombreActualizado = $nombre . '.' . $imgFileTye;
        $sql = "UPDATE Usuario 
                SET foto_perfil = '$nombreActualizado'
                WHERE user_name = '$nombre'";
        $this->database->query($sql);
    }

    public function getHeader($usuario){
          $resultado = $this->database->query("SELECT user_name
                                                FROM usuario

/*SELECT user_name , SUM(puntaje) puntuacion
                                                FROM   usuario U JOIN
                                                        partida P ON u.id_usuario = p.id_usuario*/
                                                WHERE user_name = '$usuario'");
        if($resultado && $resultado->num_rows > 0){
            $usuario = $resultado->fetch_assoc();
        }
        return $usuario;
    }

    public function verificarUsuario($token, $email){

        $tokenDB = $this->database->query_assoc("SELECT token
        FROM usuario
        WHERE email = '$email'");


        if($tokenDB[0]['token'] === $token){
            $sql = "UPDATE Usuario 
                    SET verificado = '1'
                    WHERE email = '$email'";
            $this->database->query($sql);
            return true;
            exit();
        }else{
            echo "hubo un error en la verificación, intente nuevamente.";
            var_dump($tokenDB);
            return false;
            exit();
        }

    }

    function obtenerPais($latitud, $longitud){
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitud . "," . $longitud . "&key=AIzaSyCrhbTzWlqIINJqnB_PU7XDMdXC0ObRBh4";
        $respuestaURL = file_get_contents($url);

        $datos = json_decode($respuestaURL, true);

        if($datos['status'] === 'OK'){ //chequea si hubo respuesta valida
            foreach ($datos['results'] as $resultado){
                foreach($resultado['address_components'] as $componente){
                    if(in_array('country', $componente['types'])){
                        return $componente['long_name']; //devuelve el nombre del pais
                    }
                }
            }
        }
        return 'ERROR-PAIS';
    }



}