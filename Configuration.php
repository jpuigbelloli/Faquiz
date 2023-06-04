<?php
include_once('helpers/MySqlDatabase.php');
include_once("helpers/MustacheRender.php");
include_once('helpers/Router.php');

include_once('model/SongsModel.php');
include_once('model/PerfilModel.php');
include_once ('model/UsuarioModel.php');

include_once('controller/InicioSinLogController.php');
include_once('controller/RegistroController.php');
include_once('controller/LoginController.php');
include_once('controller/PerfilController.php');

include_once('third-party/mustache/src/Mustache/Autoloader.php');


class Configuration {
    private $configFile = 'config/config.ini';

    public function __construct() {
    }

    public function getInicioSinLogController() {
        return new InicioSinLogController($this->getRenderer());
    }

    public function getRegistroController(){
        return new RegistroController(
            new UsuarioModel($this->getDatabase()),
                $this->getRenderer());
    }

    public function getLoginController(){
        return new LoginController(
            new UsuarioModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getPerfilController(){
        return new PerfilController(
            new PerfilModel($this->getDatabase()),
            $this->getRenderer());
    }
    public function getLobbyController(){
        return new LobbyController(
            new UsuarioModel($this->getDatabase()),
            $this->getRenderer());
    }

    private function getArrayConfig() {
        return parse_ini_file($this->configFile);
    }

    private function getRenderer() {
        return new MustacheRender('view/partial');
    }

    public function getDatabase() {
        $config = $this->getArrayConfig();
        return new MySqlDatabase(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['database']);
    }

    public function getRouter() {
        return new Router(
            $this,
            "getInicioSinLogController",
            "list"
    );
    }
}