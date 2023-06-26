<?php
class UsuarioModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function registrarse($nombre,$apellido,$fecha_nac,$genero, $ubicacion,$email,$user_name,$contrasenia,$foto_perfil,$token){
       $sql = 'SELECT user_name FROM Usuario';
       $usuarios = $this->database->query($sql);
       if($user_name = $usuarios){
           die('Ya existe un usuario con ese nombre');
        } else {
           $this->database->query("INSERT INTO Usuario (nombre, apellido, fecha_nac, genero, ubicacion, email, user_name, contrasenia, foto_perfil, token) 
             VALUES ('$nombre','$apellido','$fecha_nac','$genero','$ubicacion','$email','$user_name','$contrasenia','$foto_perfil', '$token')");
       }

    }


    /*INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `fecha_nac`, `genero`, `ubicacion`, `email`, `user_name`, `contrasenia`, `foto_perfil`, `fecha_ingreso`, `id_rol`) VALUES (NULL, 'pedro', 'pika', '2023-05-29 19:24:20.000000', '1', ST_GeomFromText(''), 'email@gmail.com', 'email233', 'lelele', NULL, current_timestamp(), '1')
     * */
    public function registrar($nombre,$apellido,$fecha_nac,$genero,$ubicacion,$email,$user_name,$hash,$ruta_imagen,$token){
        $query = $this->database->query(
            "INSERT INTO usuario (nombre,apellido,fecha_nac,genero,ubicacion,email,user_name,contrasenia,foto_perfil, token)
             VALUES ('$nombre','$apellido','$fecha_nac','$genero','$ubicacion','$email','$user_name','$hash','$ruta_imagen', '$token')"
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
   /* public function verificarCredenciales($usuario,$contrasenia){
        //me devuelve la contraseña hasheada
        $query = $this->database->query_normal("SELECT id_usuario,contrasenia FROM usuario
                                WHERE user_name = '$usuario'");
        //todo OR email = $usuario
        var_dump($query);
        if($query->num_rows === 1){
            $fila = $query->fetch_assoc();
            $contraseniaHasheada = $fila["contrasenia"];
            if(password_verify($contrasenia,$contraseniaHasheada)){
//            if($contrasenia == $fila["clave"]){
                session_start();
                $_SESSION['usuario']=$usuario;
                $_SESSION['id'] = $fila["id"];

                //deberia redireccionar al lobby
                header("Location:/");
                exit();
            } else {
                return "Contraseña inválida. Intentá otra vez";
            }
        } else {
//            $error["error"] = "No existe ese usuario";
            return "No existe ese usuario";
        }
    }*/

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

/*
    public function verificarUbicacion($ubicacion){
        if(empty($ubicacion) || strpos($ubicacion, ',') === false){
            die("Ubicacion vacía o inválida");
        } else{
            return $ubicacion;
        }
    }
*/
    public function verificarUsuario($token, $email){

        $tokenDB = $this->database->query("SELECT token
        FROM usuario
        WHERE email = '$email'");

        if($tokenDB == $token){
            $sql = "UPDATE Usuario 
                    SET verificado = '1'
                    WHERE email = '$email'";
            $this->database->query($sql);
            header('Location:/autenticacion?verificacion=VERIFICACIONCUENTAEXITOSA');
            exit();
        }else{
            echo "hubo un error en la verificación, intente nuevamente.";
            var_dump($tokenDB);
            header('Location:/autenticacion?verificacion=ERRORDB');
            exit();
        }

    }



}