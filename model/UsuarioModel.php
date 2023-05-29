<?php

class UsuarioModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function registrarse($nombre,$apellido,$fecha_nac,$genero, $ubicacion,$email,$user_name,$contrasenia,$foto_perfil){
       $sql = 'SELECT user_name FROM Usuario';
       $usuarios = $this->database->query($sql);
       if($user_name != $usuarios){
        return $this->database->query(
            'INSERT INTO Usuario (nombre,apellido, fecha_nac,genero, ubicacion,email,user_name,contrasenia,foto_perfil) 
             VALUES ($nombre,$apellido,$fecha_nac,$genero,$ubicacion,$email,$user_name,$contrasenia,$foto_perfil)');
        } else {
           die('Ya existe un usuario con ese nombre');
       }
    }
}