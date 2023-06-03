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

    public function tituloLobby(){
        $id_usuario = $_SESSION['id_usuario'];
        $data['nombre']['puntaje'] = $this->usuarioModel->getHeader($id_usuario);
        $this->renderer->render('Lobby',$data);
    }


}