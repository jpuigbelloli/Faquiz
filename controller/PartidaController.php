<?php

class PartidaController{

    private $renderer;
    private $partidaModel;
    private $id;
    private $respuestaCorrecta;
    private $puntaje;
    private $tiempoTotal;
    private $puntos = 0;

    public function __construct($partidaModel,$renderer){
        $this->renderer = $renderer;
        $this->partidaModel = $partidaModel;
        $this->id =0;
        $this->puntaje = 0;
        $this->tiempoTotal = 10;
    }

    public function list()
    {
//        $data["pregunta_respuestas"] = $this->partidaModel->obtenerPreguntasYRespuestas();

//        echo '<pre>';
//        var_dump($data);
//        echo '</pre>';
//        $this->respuestaCorrecta = $data["pregunta_respuestas"][0]['correcta'];
//        '<script src="./public/js/reload.js"></script>';
//        $this->responder();
        $this->renderer->render('partida');

    }

    public function nuevaPregunta(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }

        if(isset($_GET["correcto"]) || isset($_GET["incorrecto"]) || isset($_GET["puntos"])){
            $data["correcto"]=$_GET["correcto"]??"";
            $data["incorrecto"]=$_GET["incorrecto"]??"";
            $data["puntos"]=$_GET["puntos"]??"";
        }

        $data["pregunta"] = $this->partidaModel->obtenerPregunta();
        $idPregunta = $data["pregunta"][0]["id_pregunta"];
        $data["respuestas"] = $this->partidaModel->obtenerRespuestas($idPregunta);
        echo json_encode($data);


    }
    public function responder()
    {
        $respuesta = $_POST['opcion'];
        $id_pregunta = $_POST['id'];

        $correcta = $this->partidaModel->esCorrecta($id_pregunta,$respuesta);
        if ($correcta[0]["correcta"] == 1){
            $this->puntos++;
            $this->puntaje += $this->puntos;
            header("Location:/partida?correcto=1&puntos=".$this->puntos);
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

    public function esCorrecta(){
        $respuesta = $_POST['respuesta'] ?? "";
        $id_pregunta = $_POST['id_pregunta'] ?? "";
        $correcta = $this->partidaModel->esCorrecta($respuesta,$id_pregunta);
        //grabar persi
//        var_dump($correcta);
        echo json_encode($correcta);
    }


    public function getId(): int
    {
        return $this->id;
    }
    public function getRespuestaCorrecta()
    {
        return $this->respuestaCorrecta;
    }



    /*public function esCorrecta($id,$respuesta){
        $correcta = false;
        $resp_correcta =($this->partidaModel->respuestaCorrecta($id)) ;


            if ($resp_correcta[0]["respuesta"]==$respuesta) {
                     $correcta= true;

                }else {
                    var_dump($resp_correcta[0]["respuesta"]);
                    var_dump($respuesta);
                }
        return $correcta;
    }*/
    public function actualizar(){
        $preguntas = $this->partidaModel->$this->partidaModel->obtenerPreguntasYRespuestas(FALSE);
        $pagina =$this->renderer->render('partida',$preguntas);
    }




}