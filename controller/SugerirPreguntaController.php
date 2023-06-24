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
        $respuesta1 = $_POST["respuesta1Text"];
        $respuesta2 = $_POST["respuesta2Text"];
        $respuesta3 = $_POST["respuesta3Text"];
        $respuesta4 = $_POST["respuesta4Text"];
        $correcta= $_POST["correcta"];

        $this->sugerirPreguntaModel->agregarPreguntaSugerida($pregunta,$categoria,$respuesta1,$respuesta2,$respuesta3,$respuesta4,$correcta,Usuario::getID());

        header('Location:/lobby');
        exit();
    }

}