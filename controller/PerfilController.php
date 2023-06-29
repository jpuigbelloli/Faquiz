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
        $nombreDeUsuarioGet = $_GET['usuario'] ?? null;
        $nombreDeUsuarioSession = $_SESSION['usuario'] ?? null;

        if (isset($_SESSION['logueado'])) {
            // EL USUARIO ESTÁ LOGUEADO CORRECTAMENTE
            $data['logueado'] = $_SESSION['logueado'];

            if ($nombreDeUsuarioGet !== null && $nombreDeUsuarioGet !== '') {
                // Si se proporciona un nombre de usuario por GET, se asigna ese valor
                $nombreDeUsuario = $nombreDeUsuarioGet;
            } else {
                // Si no se proporciona un nombre de usuario por GET, se utiliza el nombre de usuario de la sesión
                $nombreDeUsuario = $nombreDeUsuarioSession;
            }
        } else {
            // EL USUARIO NO ESTÁ LOGUEADO
            $nombreDeUsuario = $nombreDeUsuarioGet;
        }

        if ($nombreDeUsuario !== null && $nombreDeUsuario !== '') {
            // SE HA PROPORCIONADO UN NOMBRE DE USUARIO VÁLIDO
            $data['usuario'] = $this->perfilModel->getArray($nombreDeUsuario);
            if ($data['usuario'] === null) {
                // El usuario no existe en la base de datos
                header('Location:/error?codError=222');
                exit;
            }
            $this->renderer->render('perfil', $data);
        } else {
            // NO SE HA PROPORCIONADO UN NOMBRE DE USUARIO VÁLIDO
            header('Location:/error?codError=222');
            exit;
        }
    }
}

