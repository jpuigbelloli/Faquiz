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

        $contenidoQR = "http://localhost/perfil/" . $nombreDeUsuario;

        // Guardar archivo en carpeta
        $nombreArchivoQR = $nombreDeUsuario . ".png";

        // Generar el código QR
        $rutaQR = QRHelper::generarCodigoQR($contenidoQR, $nombreArchivoQR);
        $usuario['rutaQR'] = $rutaQR;


        if ($usuario) {
            $usuario['rutaQR'] = $rutaQR;

            $data = array("usuario" => $usuario);
            var_dump($data);
            $this->renderer->render("perfil", $data);
        } else {
            //HABRIA QUE HACER UNA VISTA ERROR PARA TIRAR TODOS LOS ERRORES A ESA VISTA
            echo "Perfil no encontrado";
        }
    }

}

