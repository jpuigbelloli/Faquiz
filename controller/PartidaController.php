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

        if(isset($_SESSION['id_pregunta']) || isset($_SESSION['recargo'])){
            $this->finPorRecarga();
        }else{
            $partida = new Partida();
            $_SESSION['puntos'] = $partida->getPuntaje();
            $this->renderer->render('partida');
        }


    }
    private function finPorRecarga(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        if(isset($_SESSION['id_pregunta'])){
            $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($_SESSION["id_pregunta"],0,Usuario::getID());
            $this->partidaModel->guardarPartida(Usuario::getID(),$_SESSION['puntos']);
        }
        $data["puntos"]=$_SESSION['puntos'];
        $data["termino"] = true;


        $_SESSION['recargo'] = true;
        unset($_SESSION['id_pregunta']);
        $this->renderer->render('partida',$data);

    }

    public function irAlLobby(){
        unset($_SESSION['recargo']);
        header('Location:/lobby');
        exit();
    }

    public function nuevaPregunta(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        if(isset($_SESSION['recargo'])){
            header('Location:/partida');
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
        if(isset($_SESSION['recargo'])){
            header('Location:/partida');
            exit();
        }
        $respuesta = $_POST['respuesta'] ?? "";
        $id_pregunta = $_POST['id_pregunta'] ?? "";
        $tiempo = $_POST['tiempo'] ?? "";

        //todo cambiar a una clase para obtener ids?
        $userId = $this->partidaModel->getUserId($_SESSION['usuario']);

//        $correcta = $this->partidaModel->esCorrecta("Thomas Edison","21");

        $diferenciaTiempo = $tiempo - $_SESSION['tiempo'];
        $correcta = $this->partidaModel->esCorrecta($respuesta,$id_pregunta);

        if($correcta[0]["correcta"] == 1 && $diferenciaTiempo <= 10500){
            $_SESSION['puntos']++;
            $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($id_pregunta,1,$userId[0]["id"]);
            unset($_SESSION['id_pregunta']);
        } else {
            $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($id_pregunta,0,$userId[0]["id"]);
            $this->partidaModel->guardarPartida($userId[0]["id"],$_SESSION['puntos']);
            unset($_SESSION['id_pregunta']);
        }

        $correcta["puntos"] = $_SESSION["puntos"];
        echo json_encode($correcta);
    }

    public function fin(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        if(isset($_SESSION['recargo'])){
            header('Location:/partida');
            exit();
        }

        $userId = $this->partidaModel->getUserId($_SESSION['usuario']);
        $this->partidaModel->guardarPreguntaCorrectaOIncorrecta($_SESSION["id_pregunta"],0,$userId[0]["id"]);
        $this->partidaModel->guardarPartida($userId[0]["id"],$_SESSION['puntos']);
        unset($_SESSION['id_pregunta']);
        $data["puntaje"] = $_SESSION['puntos'];
        echo json_encode($data);
    }


    public function reportar(){
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location:/login');
            exit();
        }
        if(isset($_SESSION['recargo'])){
            header('Location:/partida');
            exit();
        }
        $id_pregunta = $_POST['id_pregunta'];
        $motivo = $_POST['motivo'];

        $this->partidaModel->reportarPregunta($id_pregunta,$motivo,Usuario::getID());

    }
}