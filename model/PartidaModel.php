<?php

class PartidaModel{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasYRespuestas($dato){
    $sql = "SELECT id_pregunta,pregunta, respuesta_A A,respuesta_B B, respuesta_C C,respuesta_d D, C.descripcion categoria
            FROM pregunta P INNER JOIN 
                categoria C ON P.id_categoria = C.id_categoria
            ORDER BY RAND() LIMIT 1;";
    $pregunta = $this->database->query_assoc($sql);
    if(empty($dato)){
        return $pregunta;
    } else{
        return $pregunta[$dato];
    }

    }

    public function agregarPartida($usuario,$puntero){
         $sql = "INSERT INTO partida (id_usuario, puntaje) VALUES 
                                       ($usuario,$puntero);";
         return$this->database->query($sql);
    }

    public function respuestaCorrecta($dato){
        $sql = "SELECT CASE 
                           WHEN respuesta_A = respuesta_correcta THEN 'A'
                           WHEN respuesta_B = respuesta_correcta THEN 'B'
                           WHEN respuesta_C = respuesta_correcta THEN 'C'
                           WHEN respuesta_D = respuesta_correcta THEN 'D'
                       END
                FROM id_pregunta = $dato";

        $resultado = $this->database->query_assoc($sql);
        return $resultado;
    }



}