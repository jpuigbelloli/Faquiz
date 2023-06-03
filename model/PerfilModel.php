<?php

class PerfilModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function obtenerIDUsuario()
    {
        return 2;
    }

    public function getData($dato)
    {
        $userID = $this->obtenerIDUsuario();
        return $this->database->query('SELECT ' . $dato . ' FROM usuario WHERE id_usuario = ' . $userID);
    }

    public function generarArray()
    {
        $arrayDatos = [];

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


       /* $arrayDatosTest = array(
            'user_name' => 'Popolon22',
            'foto_perfil' => '../public/imgs/002.png',
            'nombreCompleto' => 'Marcelo Sorrento',
            'email' => 'marchelo@gmail.com',
            'edad' => '25',
            'pais' => 'Argentina'*/
       // );

        /*echo '<pre>';
        var_dump($arrayDatosTest);
        echo '</pre>';*/

        return $arrayDatosTest;
    }

    private function generarNombreCompuesto()
    {
        $userID = $this->obtenerIDUsuario();

        $nombre = $this->getData('nombre');
        $apellido = $this->getData('apellido');

        return $nombre . ' ' . $apellido;
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
        return 25;
    }

    private function obtenerPais()
    {
        return "Argentina";
    }


}

