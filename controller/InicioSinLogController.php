<?php

class InicioSinLogController
{

    private $renderer;

    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    public function list() {
        if (isset($_SESSION['logueado'])) {
            header('Location:/lobby');
            exit();
        }

        $this->renderer->render('inicioSinLog');
    }

    public function getVistaLogin(){
        $this->renderer->render('login');
    }



    public function redirigir(){   /*Falta llamarla*/

         if(isset($_POST['registro'])){
            $this->getVistaResgistro();
        } else if(isset($_POST['iniciarSesion'])){
            $this->getVistaLogin();
        }
         else{
             $this->renderer->render('inicioSinLog');
         }

    }

    public function getVistaResgistro()
    {
        $this->renderer->render('registro');

    }


}