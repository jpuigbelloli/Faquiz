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
        $data["error"] = !empty($_GET["error"]);
        $this->renderer->render('login',$data);
    }

    public function irALogin()
    {
        $this->renderer->render('login');
    }

    public function validar(){
        $usuario = $_POST["user_name"] ?? "";
        $contrasenia = $_POST["contrasenia"] ?? "";

        $usuarioValido = $this->usuarioModel->verificarCredenciales($usuario,$contrasenia);

        if( $usuarioValido ){
            session_start();
            $_SESSION['usuario']=$usuario;
            header('Location:/');
            exit();
        } else {
            $msg = 'Usuariro o contrasenia invalida';
            header('Location:/login&error=1');
            exit();
        }
       /* $msg["mensaje"] = $this->usuarioModel->verificarCredenciales($usuario,$contrasenia);


        if($msg){
//            header('Location:/login');
            $this->renderer->render('login',$msg);
//            exit();
        }*/
    }
}