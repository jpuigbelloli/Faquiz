<?php

class QRHelper
{
    public static function generarCodigoQR($nombreDeUsuario)
    {
        // Carpeta en donde se guardan los codigosQR
        $contenidoQR = "http://localhost/perfil/" . $nombreDeUsuario;
        $directorioQR = 'public/codigosQR/';
        $nombreArchivoQR = $nombreDeUsuario . ".png";

        QRcode::png($contenidoQR, $directorioQR . $nombreArchivoQR, QR_ECLEVEL_L, 5);

        // Ruta completa
        return $directorioQR . $nombreArchivoQR;
    }
}

