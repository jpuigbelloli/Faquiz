<?php

class LobbyController
{

    private $usuarioModel;
    private $renderer;

    public function __construct($usuarioModel, $renderer){
        $this->usuarioModel = $usuarioModel;
        $this->renderer = $renderer;
    }

    public function list(){
        $a = array('a');
        $this->renderer->render('lobby',$a);
    }

    public function execute(){
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $this->usuarioModel->getHeader($usuario);
        $this->renderer->render('lobby',$data);
        if(isset($_POST['jugar'])){
            $a = array('a');
            $this->renderer->render('partida',$a);
        }
    }


}