<?php

class RevisarPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    //trae las que estan en estado pendiente de revision
    public function getPreguntasARevisar(){
        $sql = "SELECT PS.id_pregunta_sugerida AS id,PS.pregunta,C.descripcion as categoria,PS.respuesta1,PS.respuesta2,PS.respuesta3,PS.respuesta4,PS.correcta
                FROM pregunta_sugerida PS
                JOIN categoria C
                ON PS.id_categoria = C.id_categoria
                WHERE id_estado = 1";
        return $this->database->query_assoc($sql);
    }

    public function aprobarPregunta($id_pregunta_sugerida){
        $sql = "UPDATE pregunta_sugerida
                SET id_estado = 2
                WHERE id_pregunta_sugerida = '$id_pregunta_sugerida'";

        return $this->database->query($sql);
    }

    public function rechazarPregunta($id_pregunta_sugerida){
        $sql = "UPDATE pregunta_sugerida
                SET id_estado = 3
                WHERE id_pregunta_sugerida = '$id_pregunta_sugerida'";

        return $this->database->query($sql);
    }

    public function agregarPregunta($categoria,$pregunta){
            $sql = "INSERT INTO pregunta(id_categoria,pregunta,agregada)
                    VALUES ('$categoria','$pregunta',true)";

            return $this->database->query_ultimo_id($sql);
    }

    public function agregarRespuesta($preguntaID,$respuesta,$correcta){
        $sql = "INSERT INTO respuesta(id_pregunta,respuesta,correcta)
                VALUES ('$preguntaID','$respuesta','$correcta')";

        return $this->database->query($sql);
    }
}