<?php

class ErrorModel
{
    public function getErrorData($errorCode) {
        $errorData = [
            'title' => '',
            'message' => '',
            'buttonText' => '',
            'buttonLink' => ''
        ];

        switch ($errorCode) {
            case '404':
                $errorData['title'] = 'Página no encontrada';
                $errorData['message'] = 'La página que estás buscando no existe.';
                $errorData['buttonText'] = 'Volver al Inicio';
                $errorData['buttonLink'] = '/index';
                break;
            case '222':
                $errorData['title'] = 'Perfil Inexistente';
                $errorData['message'] = 'El perfil que estás buscando no existe.';
                $errorData['buttonText'] = 'Ir al Lobby';
                $errorData['buttonLink'] = '/lobby';
                break;
            case '500':
                $errorData['title'] = 'Error interno del servidor';
                $errorData['message'] = 'Ha ocurrido un error interno en el servidor.';
                $errorData['buttonText'] = 'Volver al Inicio';
                $errorData['buttonLink'] = '/index';
                break;
            default:
                $errorData['title'] = 'Error desconocido';
                $errorData['message'] = 'Ha ocurrido un error desconocido.';
                $errorData['buttonText'] = 'Volver al Inicio';
                $errorData['buttonLink'] = '/index';
                break;
        }

        return $errorData;
    }

}