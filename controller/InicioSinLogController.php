<?php

class InicioSinLogController
{

    private $renderer;

    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    public function list() {
        $a = array("a");



        $this->renderer->render('inicioSinLog', $a);
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