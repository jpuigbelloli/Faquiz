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
    //obtiene las preguntas que el usuario no respondio y las que fueron agregadas
    public function obtenerPregunta($id){
        $sql = "SELECT P.id_pregunta AS id_pregunta, P.pregunta AS pregunta, C.descripcion AS categoria FROM pregunta P
                JOIN categoria C
                ON P.id_categoria = C.id_categoria
                WHERE P.id_pregunta NOT IN( SELECT id_pregunta FROM estadistica E
                                            WHERE E.respuesta = '1' AND E.id_usuario = '$id')  
                AND P.agregada = 1
                ORDER BY RAND() LIMIT 1";

        return $this->database->query_assoc($sql);
    }

    public function obtenerRespuestas($id){
        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY respuesta) AS numero ,R.respuesta AS respuesta
                FROM respuesta R
                WHERE R.id_pregunta = '$id'";

        $data = $this->database->query_assoc($sql);
        //Randomizado del orden de las respuestas
        shuffle($data);
        return $data;
    }

    public function esCorrecta($respuesta,$id_pregunta){
        $sql = "SELECT R.correcta FROM respuesta R
                WHERE R.respuesta = '$respuesta'
                AND R.id_pregunta = '$id_pregunta'";
        //se podria sacar el AND
        return $this->database->query_assoc($sql);
    }

    public function agregarPartida($userId,$puntaje){
         $sql = "INSERT INTO partida (id_usuario, puntaje) VALUES 
                                       ('$userId','$puntaje');";
         return$this->database->query($sql);
    }
    public function guardarPreguntaCorrectaOIncorrecta($id_pregunta,$respuesta,$userId){
        $sql = "INSERT INTO `estadistica` (`id_pregunta`, `respuesta`,`id_usuario`) 
                VALUES ('$id_pregunta', '$respuesta','$userId')";

        $this->database->query($sql);
    }

    public function getUserId($username){
        $sql = "SELECT U.id_usuario AS id
                FROM usuario U
                WHERE U.user_name = '$username'";
        return $this->database->query_assoc($sql);
    }

    public function guardarPartida($id,$puntaje){
        $sql = "INSERT INTO partida (id_usuario,puntaje)
                VALUES('$id','$puntaje')";

        $this->database->query($sql);
    }

    public function reportarPregunta($id_pregunta,$motivo,$id_usuario){
        $sql = "INSERT INTO pregunta_reportada(id_pregunta_reportada,id_motivo,id_usuario)
                VALUES ('$id_pregunta','$motivo','$id_usuario')";
        $this->database->query($sql);
    }
}