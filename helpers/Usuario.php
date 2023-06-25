<?php

class Usuario
{
    public static function getID(){
        $config = parse_ini_file('config/config.ini');
        $db = new MySqlDatabase($config['servername'],
            $config['username'],
            $config['password'],
            $config['database']);

        $username = $_SESSION['usuario'];
        $resultado = $db->query_fetch_assoc("SELECT U.id_usuario AS id
                FROM usuario U
                WHERE U.user_name = '$username'");
        return $resultado["id"];
    }

    public static function getROL(){
        $config = parse_ini_file('config/config.ini');
        $db = new MySqlDatabase($config['servername'],
            $config['username'],
            $config['password'],
            $config['database']);

        $username = $_SESSION['usuario'];
        $resultado = $db->query_fetch_assoc("SELECT TU.rol AS rol
                FROM usuario U
                JOIN tipo_usuario TU
                ON U.id_rol = TU.id_rol
                WHERE U.user_name = '$username'");
        return $resultado["rol"];
    }
}