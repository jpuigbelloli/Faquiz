<?php

class UsuarioModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function verificarCredenciales($usuario,$contrasenia){
        //me devuelve la contraseña hasheada
        $query = $this->database->query_normal("SELECT id_usuario,contrasenia FROM usuario
                                WHERE user_name = '$usuario'");
        //todo OR email = $usuario

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
                echo "Contraseña inválida. Intentá otra vez";
            }
        } else {
//            $error["error"] = "No existe ese usuario";
            echo "No existe ese usuario";
        }
    }
}