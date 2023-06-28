<?php

class PerfilController
{
    private $perfilModel;
    private $renderer;


    public function __construct($perfilModel, $renderer)
    {
        $this->perfilModel = $perfilModel;
        $this->renderer = $renderer;
    }

    public function list()
    {
        $nombreDeUsuarioSession = $_SESSION['usuario'] ?? null;
        $nombreDeUsuarioGet = $_GET['usuario'] ?? null;
        $usuario = $_SESSION['usuario'] ?? "";
        $logueado = $_SESSION['logueado'] ?? "";
        $data['usuario']['user_logueado'] = $usuario;


        if (($nombreDeUsuarioSession !== null) && ($_SESSION['logueado'] === true) && ($nombreDeUsuarioGet === $_SESSION['usuario'])) {
            $dataUsuario = $this->perfilModel->getData($nombreDeUsuarioSession);
            $dataUsuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioSession);

            $data['logueado'] = $_SESSION['logueado'];
            $data['usuario'] = $dataUsuario;
            $data['usuario']['user_logueado'] = $_SESSION['usuario'];

            $this->renderer->render('perfil', $data);
            exit();
        } elseif (($nombreDeUsuarioGet !== null) && ( $logueado === true) && ($nombreDeUsuarioGet != $_SESSION['usuario'])) {
            $dataUsuario = $this->perfilModel->getData($nombreDeUsuarioGet);
            $dataUsuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioGet);

            $data['logueado'] = $_SESSION['logueado'];
            $data['usuario'] = $dataUsuario;
            $data['usuario']['user_logueado'] = $_SESSION['usuario'];

            $this->renderer->render('perfil', $data);
            exit();
        } elseif (($nombreDeUsuarioGet !== null) && empty($_SESSION['logueado'])) {
            $dataUsuario = $this->perfilModel->getData($nombreDeUsuarioGet);
            $dataUsuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioGet);

            $data['usuario'] = $dataUsuario;
            $this->renderer->render('perfil', $data);
            exit();
        } else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
            $this->renderer->render('error', $data);
        }


    }

}

