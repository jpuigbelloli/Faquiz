<?php

class RevisarPreguntaController
{
    private $renderer;
    private $revisarPreguntaModel;


    public function __construct($revisarPreguntaModel, $renderer)
    {
        $this->revisarPreguntaModel = $revisarPreguntaModel;
        $this->renderer = $renderer;
    }

    public function list(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $data["preguntas"] = $this->revisarPreguntaModel->getPreguntasSugeridasARevisar();

        foreach ($data["preguntas"] as &$pregunta) {
            $pregunta["categorias"] = array(
                "Historia" => ($pregunta["categoria"] === "Historia"),
                "Geografia" => ($pregunta["categoria"] === "Geografia"),
                "Ciencia" => ($pregunta["categoria"] === "Ciencia"),
                "Arte" => ($pregunta["categoria"] === "Arte"),
                "Deportes" => ($pregunta["categoria"] === "Deportes"),
                "Entretenimiento" => ($pregunta["categoria"] === "Entretenimiento")
            );
        }
        $data['usuario']['user_logueado'] = $_SESSION['usuario'];
        $data['logueado'] = $_SESSION['logueado'];
        $this->renderer->render('revisarPregunta',$data);
    }

    public function aprobar(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $id_pregunta = $_POST['id'];

        $this->revisarPreguntaModel->aprobarPregunta($id_pregunta);

        $pregunta = $_POST['pregunta'];
        $categoria = $_POST['categoria'];
        $respuestas = [
            $_POST['respuesta1'],
            $_POST['respuesta2'],
            $_POST['respuesta3'],
            $_POST['respuesta4']
        ];
        $correcta= $_POST["correcta"];

        $preguntaID = $this->revisarPreguntaModel->agregarPregunta($categoria,$pregunta);
        //todo se podria sacar la columna agregada de pregunta
        foreach ($respuestas as $respuesta){
            $esCorrecta = ($respuesta === $correcta) ? true : false;
            $this->revisarPreguntaModel->agregarRespuesta($preguntaID,$respuesta,$esCorrecta);
        }
    }

    public function rechazar(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $id_pregunta = $_POST['id'];
        $this->revisarPreguntaModel->rechazarPregunta($id_pregunta);
    }

    public function sugeridas(){
        $this->list();
    }

    public function reportadas(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $data["preguntas"] = $this->revisarPreguntaModel->getPreguntasReportadasARevisar();

        foreach ($data["preguntas"] as &$pregunta) {
            $pregunta["categorias"] = array(
                "Historia" => ($pregunta["categoria"] === "Historia"),
                "Geografia" => ($pregunta["categoria"] === "Geografia"),
                "Ciencia" => ($pregunta["categoria"] === "Ciencia"),
                "Arte" => ($pregunta["categoria"] === "Arte"),
                "Deportes" => ($pregunta["categoria"] === "Deportes"),
                "Entretenimiento" => ($pregunta["categoria"] === "Entretenimiento")
            );
        }

        /*echo '<pre>';
        var_dump($data);
        echo '</pre>';*/
        $data['usuario']['user_logueado'] = $_SESSION['usuario'];
        $data['logueado'] = $_SESSION['logueado'];
        $this->renderer->render('revisarPreguntaReportada',$data);
    }

    public function actualizarPregunta(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $id = $_POST['id'];
        $this->revisarPreguntaModel->updateEstadoRevisado($id);
        $id_pregunta_reportada = $_POST['id_pregunta_reportada'];
        $pregunta = $_POST['pregunta'];
        $categoria = $_POST['categoria'];
        $this->revisarPreguntaModel->updatePregunta($id_pregunta_reportada,$pregunta,$categoria);

        $respuestas = $_POST['respuestas'];

        $correcta= $_POST['correcta'];

        foreach ($respuestas as $respuesta){
            if($respuesta['respuesta'] === $correcta){
                Logger::info("paso por el true");
                $this->revisarPreguntaModel->updateRespuestas($respuesta['id_respuesta'],$respuesta['respuesta'],true);
            } else {
                Logger::info("paso por el false");

                $this->revisarPreguntaModel->updateRespuestas($respuesta['id_respuesta'],$respuesta['respuesta'],false);
            }
        }
    }

    public function deshabilitarPregunta(){
        if (!isset($_SESSION['logueado']) || Usuario::getROL()!=='EDITOR') {
            header('Location:/lobby');
            exit();
        }
        $id = $_POST['id'];
        $id_pregunta_reportada = $_POST['id_pregunta_reportada'];

        $this->revisarPreguntaModel->updateEstadoRevisado($id);

        $this->revisarPreguntaModel->deshabilitarPregunta($id_pregunta_reportada);
    }
}