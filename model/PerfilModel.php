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

    //DEPRECADO **BORRAR**
/*    public function getData($dato)
    {
        $resultado= $this->database->query("SELECT user_name, foto_perfil AS fotoPerfil,CONCAT(nombre,SPACE(1),apellido) AS nombre,
                                            email ,YEAR(current_date()) - YEAR(fecha_nac) AS edad
                                            FROM usuario
                                            WHERE user_name ='$dato'");

        if($resultado && $resultado->num_rows > 0){
            $usuario = $resultado->fetch_assoc();
        }
         return $usuario;

    }*/

    public function getData($nombreDeUsuario) {
        $resultado = $this->database->query("SELECT user_name, foto_perfil AS fotoPerfil,CONCAT(nombre,SPACE(1),apellido) AS nombre,
                                        email ,YEAR(current_date()) - YEAR(fecha_nac) AS edad
                                        FROM usuario 
                                        WHERE user_name ='$nombreDeUsuario'");

        if($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            return $usuario;
        }

        return null;
    }

    public function getDireccionQR($usuario){

        $directorioQR = 'public/codigosQR/';
        $nombreArchivoQR = $usuario . ".png";


        return $directorioQR . $nombreArchivoQR;
    }

    public function generarArray()
    {
        /*$arrayDatos = [];
        $user_name = $this->getData('user_name');
        array_push($arrayDatos, $user_name);
        $foto_perfil = $this->getData('foto_perfil');
        array_push($arrayDatos, $foto_perfil);
        $nombreCompleto = $this->generarNombreCompuesto();
        array_push($arrayDatos, $nombreCompleto);
        $email = $this->getData('email');
        array_push($arrayDatos, $email);
        $edad = $this->calcularEdad();
        array_push($arrayDatos, $edad);
        $pais = $this->obtenerPais();
        array_push($arrayDatos, $pais);

        return $arrayDatos;*/
    }

    private function generarNombreCompuesto()
    {
       /* $userID = $this->obtenerIDUsuario();

        $nombre = $this->getData('nombre');
        $apellido = $this->getData('apellido');

        return $nombre . ' ' . $apellido;*/
    }

    private function calcularEdad()
    {
//        $userID = $this->obtenerIDUsuario();
//
//        $fechaNacimiento = $this->getData($userID, 'fecha_nac');
//
//        $fechaActual = new DateTime();
//        $fechaNacimiento = new DateTime($fechaNacimiento);
//        $edad = $fechaNacimiento->diff($fechaActual)->y;
//
//        return $edad;

    }

    private function obtenerPais()
    {
        return "Argentina";
    }

    public function existeUsuario($nombreDeUsuario)
    {
        $resultado = $this->database->query("SELECT COUNT(*) AS total FROM usuario WHERE user_name ='$nombreDeUsuario'");
        $fila = $resultado->fetch_assoc();
        $totalUsuarios = $fila['total'];

        return $totalUsuarios > 0;
    }

    public function getCoordenadasUsuario($usuario){
        $resultado = $this->database->query("SELECT ubicacion                             
                                             FROM usuario 
                                             WHERE user_name ='$usuario'");

        if($resultado && $resultado->num_rows > 0) {
            $ubicacion = $resultado->fetch_assoc();
            return $ubicacion;
        }

        return null;
    }


}

