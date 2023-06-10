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

        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }

        if(isset($_GET["correcto"]) || isset($_GET["incorrecto"])){
            $data["correcto"]=$_GET["correcto"]??"";
            $data["incorrecto"]=$_GET["incorrecto"]??"";
        }
//        $data["pregunta_respuestas"] = $this->partidaModel->obtenerPreguntasYRespuestas();
        $data["pregunta"] = $this->partidaModel->obtenerPregunta();
        $idPregunta = $data["pregunta"][0]["id_pregunta"];
        $data["respuestas"] = $this->partidaModel->obtenerRespuestas($idPregunta);
//        echo '<pre>';
//        var_dump($data);
//        echo '</pre>';
//        $this->respuestaCorrecta = $data["pregunta_respuestas"][0]['correcta'];
//        '<script src="./public/js/reload.js"></script>';
//        $this->responder();
        $this->renderer->render('partida',$data);
    }

    public function responder()
    {
        $respuesta = $_GET['opcion'];
        $id_pregunta = $_GET['id'];

        $correcta = $this->partidaModel->esCorrecta($id_pregunta,$respuesta);

        if ($correcta[0]["correcta"] == 1){
            $this->puntos++;
            $this->puntaje += $this->puntos;
            header("Location:/partida?correcto=1");
            exit();
        } else {
            header("Location:/partida?incorrecto=1");
            exit();
            $this->puntos = 0;


//                $usuario = $_SESSION['usuario'];
//                $puntaje= $_GET['puntos'];
//                $this->partidaModel->crearPartida($usuario,$puntaje);



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