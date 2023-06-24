<?php

class InicioSinLogController
{

    private $renderer;

    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    public function list()
    {
        if (!empty($_SESSION['logueado'])) {
            header('Location:/lobby');
            exit();
        } else {
            $data["logueado"] = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
            $this->renderer->render('inicioSinLog', $data);
        }
    }

    public function redirigir()
    {   /*Falta llamarla*/

        if (isset($_POST['registro'])) {
            $this->getVistaResgistro();
        } else if (isset($_POST['iniciarSesion'])) {
            $this->getVistaLogin();
        } else {
            $this->renderer->render('inicioSinLog');
        }

    }

    public function getVistaResgistro()
    {
        $this->renderer->render('registro');

    }

    public function getVistaLogin()
    {
        $this->renderer->render('login');
    }

}