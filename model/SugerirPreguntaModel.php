<?php

class SugerirPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function agregarPregunta($categoria,$pregunta){
        $sql = "INSERT INTO pregunta(id_categoria,pregunta)
                VALUES ('$categoria','$pregunta')";
        return $this->database->query_ultimo_id($sql);
    }

    public function agregarRespuesta($preguntaID,$respuesta,$correcta){
        $sql = "INSERT INTO respuesta(id_pregunta,respuesta,correcta)
                VALUES ('$preguntaID','$respuesta','$correcta')";

        return $this->database->query($sql);
    }
}