<?php

class LoginController
{
    private $renderer;
    private $usuarioModel;

    public function __construct($usuarioModel,$renderer)
    {
        $this->renderer = $renderer;
        $this->usuarioModel = $usuarioModel;
    }

    public function list() {
        $this->renderer->render('login');
    }

    public function irALogin()
    {
        $this->renderer->render('login');
    }

    public function validar(){
        $usuario = $_POST["user_name"] ?? "";
        $contrasenia = $_POST["contrasenia"] ?? "";

        $msg["mensaje"] = $this->usuarioModel->verificarCredenciales($usuario,$contrasenia);


        if($msg){
            header('Location:/login');
            $this->renderer->render('login',$msg);
            exit();
        }
    }
}