<?php
class UsuarioModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

        public function registrar($nombre,$apellido,$fecha_nac,$genero,$email,$user_name,$hash,$foto_perfil){
        $query = $this->database->query(
            "INSERT INTO usuario (nombre,apellido,fecha_nac,genero,email,user_name,contrasenia,foto_perfil)
             VALUES             ('$nombre','$apellido','$fecha_nac','$genero','$email','$user_name','$hash','$foto_perfil')"
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


}