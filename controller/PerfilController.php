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
        $nombreDeUsuario = $_SESSION['usuario'] ?? $_GET['usuario'];
        $usuario = $this->perfilModel->getData($nombreDeUsuario);

        $usuario['rutaQR'] = $this->perfilModel->getDireccionQR($nombreDeUsuario);


        if ($usuario) {
            if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
                // Usuario logueado
                $usuario['user_name'] = $_SESSION['usuario'];
                $data['logueado'] = $_SESSION['logueado'];

                $data['usuario'] = $usuario;
                $this->renderer->render('perfil', $data);
            } else {
                // Usuario no logueado
                $usuario['user_name'] = $nombreDeUsuario;

                $data['usuario'] = $usuario;
                $this->renderer->render('perfil', $data);
            }

        } else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
        }
    }

}

