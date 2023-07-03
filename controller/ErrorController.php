<?php

class ErrorController{
    private $renderer;
    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    /*public function list() {
        $a = array("a");
        $this->renderer->render('error', $a);
    }*/


    public function gestorDeErrores($errorCode)
    {
        $errorMensaje = $this->getMensajeError($errorCode);

        $this->renderer->render('error',$errorMensaje);
    }

    private function getMensajeError($errorCode)
    {
        $errorMessages = [
            '404' => 'Página no encontrada',
            '222' => 'Perfil Inexistente',
            '500' => 'Error interno del servidor',
        ];

        return $errorMessages[$errorCode] ?? 'Error desconocido';
    }

    private function renderErrorView($errorCode, $errorMessage)
    {
        // Renderiza la vista de error pasando el código de error y el mensaje de error
        // Puedes usar tu motor de plantillas preferido para renderizar la vista
        // Aquí hay un ejemplo simple utilizando HTML:
        include 'views/error.php';
    }


}