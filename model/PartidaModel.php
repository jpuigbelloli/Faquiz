<?php

class PartidaModel{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPregunta(){
        $sql = "SELECT P.id_pregunta ID,P.pregunta, C.descripcion categoria
            FROM pregunta P INNER JOIN 
                categoria C 
                ON C.id_categoria = P.id_categoria 
            ORDER BY RAND() LIMIT 1";
        return $this->database->query_assoc($sql);
    }
    public function obtenerRespuestas($id_pregunta){
        $sql = "SELECT  R.respuesta, R.correcta 
                FROM respuesta R INNER JOIN 
                    pregunta P ON R.id_pregunta = P.id_pregunta
                     WHERE P.id_pregunta = $id_pregunta 
                       AND R.id_respuesta IN (SELECT id_respuesta FROM respuesta)";

        $respuestas = $this->database->query_assoc($sql);

            $array['respuestas']= array();
            $array['A']=$respuestas[0]['respuesta'];
            $array['B']=$respuestas[1] ['respuesta'];
            $array['C']=$respuestas[2] ['respuesta'];
            $array['D']=$respuestas[3] ['respuesta'];
            $array['esCorrecta'] = $respuestas[0]['respuesta'];

        $respuestaCorrecta = $this->respuestaCorrecta($id_pregunta);

        foreach ($respuestas as $respuesta){
            if($respuesta == $respuestaCorrecta){
                $array['esCorrecta'] = $respuesta['i'];
            }
        }


        return $array;
    }

    public function agregarPartida($usuario,$anotador){
         $sql = "INSERT INTO partida (id_usuario, puntaje) VALUES 
                                       ($usuario,$anotador);";
         return$this->database->query($sql);
    }

    public function respuestaCorrecta($id_pregunta){
        $sql = "SELECT R.respuesta 
                FROM respuesta R  
                WHERE R.id_pregunta = $id_pregunta AND 
                      R.correcta = 1";
        return $this->database->query_assoc($sql);
        }



}