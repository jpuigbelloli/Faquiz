<?php

class PartidaController{

    private $renderer;
    private $partidaModel;
    private $id;
    private $respuestaCorrecta;
    private $puntos;
    private $puntaje;
    private $tiempoTotal;

    public function __construct($partidaModel,$renderer){
        $this->renderer = $renderer;
        $this->partidaModel = $partidaModel;
        $this->id =0;
        $this->puntaje = 0;
        $this->puntos = 0;
        $this->tiempoTotal = 10;
    }

    public function list()
    {
        $data["pregunta_respuestas"] = $this->partidaModel->obtenerPreguntasYRespuestas();
        $this->respuestaCorrecta = $data["pregunta_respuestas"][0]['correcta'];
//        '<script src="./public/js/reload.js"></script>';
        $this->id = $data["pregunta_respuestas"][0]["id_pregunta"];
        $this->responder();
        var_dump($this->id);
        $this->renderer->render('partida', $data);
    }

    public function responder()
    {
        $respuesta = $_GET['opcion'];
        $id = $this->getId();
        if (isset($respuesta)) {
            if ($this->esCorrecta($id,$respuesta)){
                $this->puntos++;
                $this->puntaje += $this->puntos;
                echo "Correcto!";

            } else {
                echo "Respuesta Incorrecta";
                $this->puntos = 0;
                var_dump($respuesta);

//                $usuario = $_SESSION['usuario'];
//                $puntaje= $_GET['puntos'];
//                $this->partidaModel->crearPartida($usuario,$puntaje);



            }
        }
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getRespuestaCorrecta()
    {
        return $this->respuestaCorrecta;
    }



    public function esCorrecta($id,$respuesta){
        $correcta = false;
        $resp_correcta =($this->partidaModel->respuestaCorrecta($id)) ;


            if ($resp_correcta[0]["respuesta"]==$respuesta) {
                     $correcta= true;

                }else {
                    var_dump($resp_correcta[0]["respuesta"]);
                    var_dump($respuesta);
                }
        return $correcta;
    }
    public function actualizar(){
        $preguntas = $this->partidaModel->$this->partidaModel->obtenerPreguntasYRespuestas(FALSE);
        $pagina =$this->renderer->render('partida',$preguntas);
    }




}