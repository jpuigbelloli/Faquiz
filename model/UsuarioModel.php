<?php

class UsuarioModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function verificar($usuario,$contrasenia){
        //me devuelve la contraseña hasheada
        $query = $this->database->query("SELECT id,clave FROM usuario
                                WHERE username = '$usuario'");
        //todo OR email = $usuario

        if($query->num_rows === 1){
            $fila = $query->fetch_assoc();
            $contraseniaHasheada = $fila["clave"];
//            if(password_verify($contrasenia,$contraseniaHasheada)){
            if($contrasenia == $fila["clave"]){
                session_start();
                $_SESSION['usuario']=$usuario;
                $_SESSION['id'] = $fila["id"];

                header("Location:/");
                exit();
            } else {
                return "Contraseña inválida. Intentá otra vez";
            }
        } else {
            return "No existe ese usuario";
        }
    }
}