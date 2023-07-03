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
                $this->manejoError->gestorDeErrores('222');
            }
            $this->renderer->render('perfil', $data);
        } else {
            // NO SE HA PROPORCIONADO UN NOMBRE DE USUARIO VÁLIDO
            $this->manejoError->gestorDeErrores('222');
        }
    }
}

