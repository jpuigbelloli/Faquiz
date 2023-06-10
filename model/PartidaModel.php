<?php

class PartidaModel{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasYRespuestas(){
    $sql = "SELECT id_pregunta,pregunta, respuesta_A AS A,respuesta_B AS B, respuesta_C AS C,respuesta_d AS D, C.descripcion categoria, P.respuesta_Correcta correcta
            FROM pregunta P INNER JOIN 
                categoria C ON P.id_categoria = C.id_categoria
            ORDER BY RAND() LIMIT 1";
    return $this->database->query_assoc($sql);
    }

    public function obtenerPregunta(){
        $sql = "SELECT P.id_pregunta AS id_pregunta, P.pregunta AS pregunta, C.descripcion AS categoria FROM pregunta P
                JOIN categoria C
                ON P.id_categoria = C.id_categoria
                ORDER BY RAND() LIMIT 1";

        return $this->database->query_assoc($sql);
    }

    public function obtenerRespuestas($id){
        $sql = "SELECT R.respuesta AS respuesta
                FROM respuesta R
                WHERE R.id_pregunta = '$id'";

        return $this->database->query_assoc($sql);
    }

    public function esCorrecta($id,$respuesta){
        $sql = "SELECT R.correcta FROM respuesta R
                WHERE R.respuesta = '$respuesta'
                AND R.id_pregunta = '$id'";
        //se podria sacar el AND

        return $this->database->query_assoc($sql);
    }

    public function agregarPartida($usuario,$puntero){
         $sql = "INSERT INTO partida (id_usuario, puntaje) VALUES 
                                       ($usuario,$puntero);";
         return$this->database->query($sql);
    }

    public function respuestaCorrecta($id_pregunta){
        $sql = "SELECT CASE 
                           WHEN respuesta_A = respuesta_correcta THEN 'A'
                           WHEN respuesta_B = respuesta_correcta THEN 'B'
                           WHEN respuesta_C = respuesta_correcta THEN 'C'
                           WHEN respuesta_D = respuesta_correcta THEN 'D'
                       END AS respuesta
                FROM pregunta
                WHERE id_pregunta = '$id_pregunta'";
        return $this->database->query_assoc($sql);

        }



}