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
        $a = array("a");
        $this->renderer->render('login', $a);
    }

    public function irALogin()
    {
        $this->renderer->render('login');
    }

    public function validar(){
        $usuario = $_POST["usuario"] ?? "";
        $contrasenia = $_POST["contrasenia"] ?? "";

        $datos = $this->usuarioModel->verificar($usuario,$contrasenia);

        var_dump($datos);
    }
}