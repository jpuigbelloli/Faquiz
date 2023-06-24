<?php

class SugerirPreguntaController
{
    private $renderer;
    private $sugerirPreguntaModel;


    public function __construct($sugerirPreguntaModel, $renderer)
    {
        $this->sugerirPreguntaModel = $sugerirPreguntaModel;
        $this->renderer = $renderer;
    }

    public function list(){
        $this->renderer->render('sugerirPregunta');
    }

    public function agregar(){

        $pregunta = $_POST["pregunta"];
        $categoria = $_POST["categoria"];
        $respuestas = [
            $_POST["respuesta1Text"],
            $_POST["respuesta2Text"],
            $_POST["respuesta3Text"],
            $_POST["respuesta4Text"]
        ];

        $correcta= $_POST["correcta"];
        $preguntaID = $this->sugerirPreguntaModel->agregarPregunta($categoria,$pregunta);

        foreach ($respuestas as $respuesta){
            $esCorrecta = ($respuesta === $correcta) ? true : false;
            $this->sugerirPreguntaModel->agregarRespuesta($preguntaID,$respuesta,$esCorrecta);
        }

        header('Location:/lobby');
        exit();

    }

}