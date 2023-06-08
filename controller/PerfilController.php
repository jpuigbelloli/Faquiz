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
        var_dump($_SESSION);
        $nombreDeUsuario = $_GET['usuario'] ?? $_SESSION['usuario'];
        $usuario = $this->perfilModel->getData($nombreDeUsuario);

        if ($usuario) {
            $data = array("usuario" => $usuario);  // Utiliza la clave "usuario" en lugar de la variable $usuario
            $this->renderer->render("perfil", $data);
        } else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
        }
    }

}

