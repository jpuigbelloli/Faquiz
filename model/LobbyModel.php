<?php

class LobbyModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    /*public function getUserId($username){
        $sql = "SELECT U.id_usuario AS id
                FROM usuario U
                WHERE U.user_name = '$username'";
        return $this->database->query_fetch_assoc($sql);
    }*/

    public function getPartidas($id){
        $sql = "SELECT P.id_partida, P.fecha, P.puntaje
                FROM partida P
                WHERE id_usuario = '$id'
                ORDER BY P.fecha DESC
                LIMIT 10";
        $resultado = $this->database->query_assoc($sql);

        //conversion de la fecha a tipo d/m/y
        foreach ($resultado as &$result){
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $result["fecha"]);
            $formattedDate = $date->format('d/m/y');
            $result["fecha"] = $formattedDate;
        }

        return $resultado;
    }

    public function getPuntosAcumuladosYPartidasJugadas($id){
        $sql = "SELECT SUM(P.puntaje) as total,COUNT(*) as partidas
                FROM partida P
                WHERE P.id_usuario = '$id'";
        return $this->database->query_assoc($sql);
    }

    public function getRankingGlobal(){
        $sql = "SELECT U.id_usuario AS id, U.user_name AS user, SUM(p.puntaje) AS puntaje, ROW_NUMBER() OVER (ORDER BY puntaje DESC) AS puesto
                FROM usuario U
                JOIN partida P
                ON U.id_usuario = P.id_usuario
                WHERE P.puntaje > 0
                GROUP BY U.user_name
                ORDER BY puntaje DESC";

        return $this->database->query_assoc($sql);
    }
}