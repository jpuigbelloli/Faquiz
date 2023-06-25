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
        $data["preguntas"] = $this->revisarPreguntaModel->getPreguntasARevisar();

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
}