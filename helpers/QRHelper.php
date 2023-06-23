<?php

class QRHelper
{
    public static function generarCodigoQR($nombreDeUsuario)
    {
        // Carpeta en donde se guardan los codigosQR
        $directorioQR = 'public/codigosQR/';

        $contenidoQR = "http://localhost/perfil/" . $nombreDeUsuario;
        $nombreArchivoQR = $nombreDeUsuario . ".png";

        QRcode::png($contenidoQR, $directorioQR . $nombreArchivoQR, QR_ECLEVEL_L, 5);

        // Ruta completa
        return $directorioQR . $nombreArchivoQR;
    }
}

