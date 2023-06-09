<?php

class Router {

    private $configuration;
    private $defaultController;
    private $defaultMethod;

    public function __construct($configuration, $defaultController, $defaultMethod) {
        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    //DEPRECADO **BORRAR**
/*    public function route($module, $method) {
        $controller = $this->getControllerFrom($module);
        $this->executeMethodFromController($controller,$method);
    }*/



    public function route($module, $method) {
        if ($module === 'perfil') {
            $controller = $this->configuration->getPerfilController();
            $nombreDeUsuario = $_GET['usuario'] ?? '';
            $controller->list($nombreDeUsuario);
        } else {
            $controller = $this->getControllerFrom($module);
            $this->executeMethodFromController($controller, $method);
        }
    }


    private function getControllerFrom($module) {
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method) {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        call_user_func(array($controller, $validMethod));
    }

}