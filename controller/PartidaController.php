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
        $data['preguntas'] = $this->partidaModel->obtenerPreguntasYRespuestas();
        $id = $data['preguntas']['id_pregunta'];
        $this->esCorrecta($id);
        '<script src="./public/js/reload.js"></script>';
        $this->renderer->render('partida', $data);



    }



    public function crear(){
        $puntos = 0;

/*
       $usuario = $_SESSION['usuario'];
       $puntaje= $_GET['puntos'];
        $partida = $this->partidaModel->crearPartida($usuario,$puntaje);*/

    }

    public function obtenerIdPregunta($id){
        return $id;
    }

    public function esCorrecta($data){
        $correcta = false;
        $resp_correcta = $this->partidaModel->respuestaCorrecta($data);
        if (isset($_POST['A']) || isset($_POST['B']) || isset($_POST['C']) || isset($_POST['D'])) {
            $respuestas = [
                $a = $_POST['A'],
                $b = $_POST['B'],
                $c = $_POST['C'],
                $d = $_POST['D']
            ];
            foreach ($respuestas as $respuesta) {
                if ($respuesta == $resp_correcta) {
                     $correcta= true;
                }
                else{
                    $correcta= false;
                }
            }

        } return $correcta;
    }
    public function actualizar(){
        $preguntas = $this->partidaModel->$this->partidaModel->obtenerPreguntasYRespuestas(FALSE);
        $pagina =$this->renderer->render('partida',$preguntas);
    }




}