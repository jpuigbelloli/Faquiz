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
        $this->renderer->render('partida');
    }

    public function nuevaPregunta(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        //Traigo la pregunta al azar
        $data["pregunta"] = $this->partidaModel->obtenerPregunta();

        //guardo el id de pregunta
        $idPregunta = $data["pregunta"][0]["id_pregunta"];

        //traigo respuestas de acuerdo al id de pregunta
        $data["respuestas"] = $this->partidaModel->obtenerRespuestas($idPregunta);

        echo json_encode($data);
    }
    public function esCorrecta(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        $respuesta = $_POST['respuesta'] ?? "";
        $id_pregunta = $_POST['id_pregunta'] ?? "";

        $correcta = $this->partidaModel->esCorrecta($respuesta,$id_pregunta);

        //grabar persistencia?
        echo json_encode($correcta);
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