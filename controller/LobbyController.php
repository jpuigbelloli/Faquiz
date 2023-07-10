<?php

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

        if(isset($_SESSION['recargo'])){
            header('Location:/partida');
            exit();
        }
        $data['esEditor'] = (Usuario::getROL() === 'EDITOR');
        $data['esAdmin'] = (Usuario::getROL() === 'ADMINISTRADOR');
        $data['esJugador'] = (Usuario::getROL() === 'JUGADOR');
        $data['usuario'] = $this->usuarioModel->getHeader($_SESSION['usuario']);
        $data['partidas'] = $this->lobbyModel->getPartidas(Usuario::getID());
        $data['datos'] = $this->lobbyModel->getPuntosAcumuladosYPartidasJugadas(Usuario::getID());
        $data['ranking'] = $this->lobbyModel->getRankingGlobal();
        $data['logueado'] = $_SESSION['logueado'];
        $data['usuario']['user_logueado'] = $_SESSION['usuario'];
        $this->renderer->render('lobby', $data);
    }
}