<?php
class UsuarioModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function registrarse($nombre,$apellido,$fecha_nac,$genero, $ubicacion,$email,$user_name,$contrasenia,$foto_perfil){
       $sql = 'SELECT user_name FROM Usuario';
       $usuarios = $this->database->query($sql);
       if($user_name = $usuarios){
           die('Ya existe un usuario con ese nombre');
        } else {
           $this->database->query("INSERT INTO Usuario (nombre,apellido, fecha_nac,genero, ubicacion,email,user_name,contrasenia,foto_perfil) 
             VALUES ('$nombre','$apellido','$fecha_nac','$genero','$ubicacion','$email','$user_name','$contrasenia','$foto_perfil')");
       }

    }


    /*INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `fecha_nac`, `genero`, `ubicacion`, `email`, `user_name`, `contrasenia`, `foto_perfil`, `fecha_ingreso`, `id_rol`) VALUES (NULL, 'pedro', 'pika', '2023-05-29 19:24:20.000000', '1', ST_GeomFromText(''), 'email@gmail.com', 'email233', 'lelele', NULL, current_timestamp(), '1')
     * */
    public function registrar($nombre,$apellido,$fecha_nac,$genero,$email,$user_name,$hash){
        $query = $this->database->query_normal(
            "INSERT INTO usuario (nombre,apellido,fecha_nac,genero,email,user_name,contrasenia)
             VALUES ('$nombre','$apellido','$fecha_nac','$genero','$email','$user_name','$hash')"
        );
    }

    public function validarUsername($username){
        $query = $this->database->query_normal(
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
                WHERE user_name = '$usuario' AND contrasenia = MD5('$contrasenia')";

        $query = $this->database->query_normal($sql);

        return $query->num_rows == 1;
    }

}