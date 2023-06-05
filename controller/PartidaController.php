<?php

class PartidaController{

    private $renderer;
    private $partidaModel;

    public function __construct($partidaModel,$renderer){
        $this->renderer = $renderer;
        $this->partidaModel = $partidaModel;
    }

    public function list()
    {
        /*$data['preguntas'] = $this->partidaModel->obtenerPreguntasYRespuestas(FALSE);
        $this->renderer->render('partida', $data);*/

        $data["pregunta_respuestas"] = $this->partidaModel->obtenerPreguntasYRespuestas();
        $data["pregunta_respuestas"][0]["nro_correctas"] = 0;
        $this->renderer->render('partida',$data);

    }



    public function crear(){
        $puntos = 0;
        $correcta = $this->partidaModel->respuestaCorrecta($data['id_pregunta']);
        if (isset($_POST['A']) || isset($_POST['B']) || isset($_POST['C']) || isset($_POST['D'])) {
            $respuestas = [$a = $_POST['A'],
                $b = $_POST['B'],
                $c = $_POST['C'],
                $d = $_POST['D']];
            foreach ($respuestas as $respuesta) {
                if ($respuesta == $correcta) {
                    $puntos += 1;
                    header('Location:/partida');
                    exit();

                }
            }

        }
/*
       $usuario = $_SESSION['usuario'];
       $puntaje= $_GET['puntos'];
        $partida = $this->partidaModel->crearPartida($usuario,$puntaje);*/

    }




}