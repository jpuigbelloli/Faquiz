<?php

class PerfilController
{
    private $perfilModel;
    private $renderer;
    private $manejoError;


    public function __construct($perfilModel, $renderer, $manejoError)
    {
        $this->perfilModel = $perfilModel;
        $this->renderer = $renderer;
        $this->manejoError = $manejoError;
    }

    public function list()
    {
        $nombreDeUsuarioGet = $_GET['usuario'] ?? null;
        $nombreDeUsuarioSession = $_SESSION['usuario'] ?? null;

        if (isset($_SESSION['logueado'])) {
            // EL USUARIO ESTÁ LOGUEADO CORRECTAMENTE
            $data['logueado'] = $_SESSION['logueado'];

            if ($nombreDeUsuarioGet !== null && $nombreDeUsuarioGet !== '') {
                $nombreDeUsuario = $nombreDeUsuarioGet;
            } else {
                $nombreDeUsuario = $nombreDeUsuarioSession;
            }
        } else {
            // EL USUARIO NO ESTÁ LOGUEADO
            $nombreDeUsuario = $nombreDeUsuarioGet;
        }

        if ($nombreDeUsuario !== null && $nombreDeUsuario !== '') {
            $data['usuario'] = $this->perfilModel->getArray($nombreDeUsuario);
            if ($data['usuario'] === null) {
                header('Location: http://localhost/error?codError=222');
            }
            $this->renderer->render('perfil', $data);
        } else {
            header('Location: http://localhost/error?codError=222');
        }
    }
}

