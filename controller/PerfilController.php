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
        $nombreDeUsuario = $_GET['usuario'] ?? $_SESSION['usuario'];
        $usuario = $this->perfilModel->getData($nombreDeUsuario);


        if ($usuario) {
            $data = array("usuario" => $usuario);
            $this->renderer->render("perfil", $data);
        } else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
        }
    }

}

