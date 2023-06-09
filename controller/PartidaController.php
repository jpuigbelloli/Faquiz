<?php
class PartidaController{

    private $renderer;
    private $partidaModel;
    private $id;
    private $respuestaCorrecta;
    private $puntos;
    private $puntaje;
    private $temporizador;

    public function __construct($partidaModel,$renderer){
        $this->renderer = $renderer;
        $this->partidaModel = $partidaModel;
        $this->respuestaCorrecta = NULL;
        $this->id =0;
        $this->puntaje = 0;
        $this->puntos = 0;
    }

    public function list()
    {
        $_SESSION['pregunta']           =   $this->partidaModel->obtenerPregunta();
        $this->id                   =   $_SESSION['pregunta'][0]['ID'];
        $_SESSION['respuestas']         =   $this->partidaModel->obtenerRespuestas($this->id);
        $respuestaCorrecta          = $_SESSION['respuestas']['esCorrecta'];
        $this->responder($respuestaCorrecta);
//        '<script src="./public/js/reload.js"></script>';
        $this->renderer->render('partida',$_SESSION );
    }

    public function responder($respuestaCorrecta){
            $id = $_SESSION['pregunta'][0]['ID'];
           $this->temporizador= $_SESSION['temporizador'] = time();

            $respuesta = isset($_POST['respuesta']) ? $_POST['respuesta'] : '';

            if ($_SESSION['pregunta'][0]['ID'] === $id && $respuesta != '') {
                if ($respuesta == $respuestaCorrecta && $this->temporizador < 10) {
                    $this->puntos++;
                    $this->puntaje += $this->puntos;
                    echo "Correcto!";
                    header('Location:/partida');
                } else {
                    echo "Respuesta Incorrecta";
                    $this->puntos = 0;
                    var_dump($respuesta);
                    '<br>';
                    var_dump($respuestaCorrecta);
                    echo $this->temporizador;
                }
            }
        }


//                $usuario = $_SESSION['usuario'];
//                $puntaje= $_GET['puntos'];
//                $this->partidaModel->crearPartida($usuario,$puntaje);


    public function getId(): int
    {
        return $this->id;
    }
    public function getRespuestaCorrecta()
    {
        return $this->respuestaCorrecta;
    }



   /* public function esCorrecta($id){
        $respuesta = $_POST['respuesta'];
        $correcta = false;
        $resp_correcta =($this->partidaModel->respuestaCorrecta($id)) ;


            if ($respuesta==$resp_correcta) {
                     $correcta= true;

                }else {

                   var_dump($resp_correcta);
                    echo($respuesta);
                }
        return $correcta;
    }*/





}