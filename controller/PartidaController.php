<?php

include_once ('helpers/Partida.php');
require_once 'helpers/Usuario.php';
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
        //tiempo actual en ms en el que guardo en session
        $currentTime = round(microtime(true) * 1000);
        $_SESSION["tiempo"] = $currentTime;

        //Traigo la pregunta al azar
        $data["pregunta"] = $this->partidaModel->obtenerPregunta(Usuario::getID());

        //guardo el id de pregunta en sesion
        $idPregunta = $data["pregunta"][0]["id_pregunta"];
        //guardo el id_pregunta actual en sesion
        $_SESSION["id_pregunta"] = $idPregunta;

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
        $tiempo = $_POST['tiempo'] ?? "";

        //todo cambiar a una clase para obtener ids?
        $userId = $this->partidaModel->getUserId($_SESSION['usuario']);

//        $correcta = $this->partidaModel->esCorrecta("Thomas Edison","21");

        $diferenciaTiempo = $tiempo - $_SESSION['tiempo'];
        Logger::info("diferencia de tiempo entre que se mando la pregunta y respuesta en ms ".$diferenciaTiempo);
        $correcta = $this->partidaModel->esCorrecta($respuesta,$id_pregunta);

        if($correcta[0]["correcta"] == 1 && $diferenciaTiempo <= 10500){
            $_SESSION['puntos']++;
            Logger::info($_SESSION['puntos']);
            $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($id_pregunta,1,$userId[0]["id"]);
        } else {
            $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($id_pregunta,0,$userId[0]["id"]);
            $this->partidaModel->guardarPartida($userId[0]["id"],$_SESSION['puntos']);
        }

        $correcta["puntos"] = $_SESSION["puntos"];
        echo json_encode($correcta);
    }

    public function fin(){
        $userId = $this->partidaModel->getUserId($_SESSION['usuario']);
        $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($_SESSION["id_pregunta"],0,$userId[0]["id"]);
        $this->partidaModel->guardarPartida($userId[0]["id"],$_SESSION['puntos']);

        $data["puntaje"] = $_SESSION['puntos'];
        echo json_encode($data);
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