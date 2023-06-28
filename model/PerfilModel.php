<?php

class PerfilModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    /* private function obtenerIDUsuario()
     {
         return 32;
     }*/

    public function getData($nombreDeUsuario)
    {
        $resultado = $this->database->query("SELECT user_name, foto_perfil AS fotoPerfil, nombre, apellido, email, fecha_nac FROM usuario WHERE user_name = '$nombreDeUsuario'");

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            return $usuario;
        }
        return null;
    }


    public function getArray($nombreDeUsuario)
    {
        $usuario = $this->getData($nombreDeUsuario);
        if (!$usuario) {
            return null; // Usuario no encontrado
        }

        $arrayDatos = [
            'user_name' => $usuario['user_name'],
            'fotoPerfil' => $usuario['fotoPerfil'],
            'nombreCompleto' => $this->generarNombreCompleto($usuario['nombre'], $usuario['apellido']),
            'email' => $usuario['email'],
            'edad' => $this->calcularEdad($usuario['fecha_nac']),
            'rutaQR' => $this->getDireccionQR($usuario),
            'ubicacion' => $this->getCoordenadasUsuario($usuario),
        ];

        return $arrayDatos;
    }

    private function generarNombreCompleto($nombre, $apellido)
    {
        return $nombre . ' ' . $apellido;
    }

    private function calcularEdad($fechaNacimiento)
    {
        $fechaActual = new DateTime();
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $edad = $fechaNacimiento->diff($fechaActual)->y;

        return $edad;
    }



    public function getDireccionQR($usuario)
    {

        $directorioQR = 'public/codigosQR/';
        $nombreArchivoQR = $usuario['user_name'] . ".png";


        return $directorioQR . $nombreArchivoQR;
    }

    public function existeUsuario($nombreDeUsuario)
    {
        $resultado = $this->database->query("SELECT COUNT(*) AS total FROM usuario WHERE user_name ='$nombreDeUsuario'");
        $fila = $resultado->fetch_assoc();
        $totalUsuarios = $fila['total'];

        return $totalUsuarios > 0;
    }

    public function getCoordenadasUsuario($usuario)
    {
        $resultado = $this->database->query("SELECT ubicacion                             
                                             FROM usuario 
                                             WHERE user_name ='".$usuario['user_name']."'");

        if ($resultado && $resultado->num_rows > 0) {
            $ubicacion = $resultado->fetch_assoc();
            return $ubicacion['ubicacion'];
        }

        return null;
    }



}

