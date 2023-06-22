<?php
require_once 'helpers/Usuario.php';
class LobbyController
{
    private $usuarioModel;
    private $lobbyModel;
    private $renderer;

    public function __construct($usuarioModel,$lobbyModel,$renderer)
    {
        $this->usuarioModel = $usuarioModel;
        $this->renderer = $renderer;
        $this->lobbyModel = $lobbyModel;
    }

    public function list()
    {

        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location: /login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $this->usuarioModel->getHeader($usuario);
        $data['partidas'] = $this->lobbyModel->getPartidas(Usuario::getID());
        $data['datos'] = $this->lobbyModel->getPuntosAcumuladosYPartidasJugadas(Usuario::getID());
        $this->renderer->render('lobby', $data);
    }

    /*public function execute(){
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $this->usuarioModel->getHeader($usuario);
        $this->renderer->render('lobby',$data);
        if(isset($_POST['jugar'])){
            $a = array('a');
            $this->renderer->render('partida',$a);
        }
    }*/


}