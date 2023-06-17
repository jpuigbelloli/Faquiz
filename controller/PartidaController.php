<?php

include_once ('helpers/Partida.php');

class PartidaController{

    private $renderer;
    private $partidaModel;

    public function __construct($partidaModel,$renderer){
        $this->renderer = $renderer;
        $this->partidaModel = $partidaModel;
    }

    public function list()
    {
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }

        $partida = new Partida();
        $_SESSION['puntos'] = $partida->getPuntaje();
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
    public function responder(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        $respuesta = $_POST['respuesta'] ?? "";
        $id_pregunta = $_POST['id_pregunta'] ?? "";
        $userId = $this->partidaModel->getUserId($_SESSION['usuario']);

//        $correcta = $this->partidaModel->esCorrecta("Thomas Edison","21");
        $correcta = $this->partidaModel->esCorrecta($respuesta,$id_pregunta);
        //grabar persistencia?

        if($correcta[0]["correcta"] == 1){
            $_SESSION['puntos']++;
            Logger::info($_SESSION['puntos']);
        } else {
            $this->partidaModel->guardarPartida($userId[0]["id"],$_SESSION['puntos']);
        }
        $this->partidaModel->guardarPreguntaCorrecta($id_pregunta,$correcta[0]["correcta"],$userId[0]["id"]);
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




}