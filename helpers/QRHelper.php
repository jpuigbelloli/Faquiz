<?php

class QRHelper
{
    public static function generarCodigoQR($contenido, $nombreArchivo)
    {
        // Carpeta en donde se guardan los codigosQR
        $directorioQR = 'public/codigosQR/';

        QRcode::png($contenido, $directorioQR . $nombreArchivo, QR_ECLEVEL_L, 5);

        // Ruta completa
        return $directorioQR . $nombreArchivo;
    }
}

