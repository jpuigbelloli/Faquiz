<?php

class SugerirPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function agregarPreguntaSugerida($pregunta,$categoria,$respuesta1,$respuesta2,$respuesta3,$respuesta4,$correcta,$id_usuario){
        $sql = "INSERT INTO pregunta_sugerida(pregunta,id_categoria,respuesta1,respuesta2,respuesta3,respuesta4,correcta,id_usuario)
                VALUES('$pregunta','$categoria','$respuesta1','$respuesta2','$respuesta3','$respuesta4','$correcta','$id_usuario')";

        return $this->database->query($sql);
    }
}