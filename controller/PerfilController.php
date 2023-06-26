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



        if (($nombreDeUsuarioGet !== null) && empty($_SESSION['logueado'])) {
            $datausuario = $this->perfilModel->getData($nombreDeUsuarioGet);
            $datausuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioGet);

            $data['usuario'] = $datausuario;
            $this->renderer->render('perfil', $data);
            exit();
        } elseif (($nombreDeUsuarioSession !== null) && ($_SESSION['logueado'] === true) && ($nombreDeUsuarioGet === $_SESSION['usuario'])) {
            $datausuario = $this->perfilModel->getData($nombreDeUsuarioSession);
            $datausuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioSession);

            $data['logueado'] = $_SESSION['logueado'];
            $data['usuario'] = $datausuario;
            $this->renderer->render('perfil', $data);
            exit();
        }elseif (($nombreDeUsuarioGet !== null) && ($_SESSION['logueado'] === true) && ($nombreDeUsuarioGet != $_SESSION['usuario'])) {
            $datausuario = $this->perfilModel->getData($nombreDeUsuarioGet);
            $datausuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuarioGet);

            $data['logueado'] = $_SESSION['logueado'];
            $data['usuario'] = $datausuario;
            $this->renderer->render('perfil', $data);
            exit();

        }else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
        }


    }

}

