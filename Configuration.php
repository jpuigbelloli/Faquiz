<?php
session_start();

//LIBRERIAS EXTERNAS
include_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once('third-party/phpqrcode/qrlib.php');
include_once('third-party/PHPMailer/src/PHPMailer.php');
include_once('third-party/PHPMailer/src/Exception.php');
include_once('third-party/PHPMailer/src/SMTP.php');


//HELPERS
include_once('helpers/MySqlDatabase.php');
include_once("helpers/MustacheRender.php");
include_once('helpers/Router.php');
include_once('helpers/QRHelper.php');
include_once('helpers/Logger.php');
require_once 'helpers/Usuario.php';

//MODELS
include_once('model/PerfilModel.php');
include_once ('model/UsuarioModel.php');
include_once ('model/PartidaModel.php');
include_once ('model/LobbyModel.php');
include_once ('model/SugerirPreguntaModel.php');
include_once('model/RevisarPreguntaModel.php');

//CONTROLLERS
include_once('controller/InicioSinLogController.php');
include_once('controller/RegistroController.php');
include_once('controller/LoginController.php');
include_once('controller/PerfilController.php');
include_once ('controller/LobbyController.php');
include_once ('controller/PartidaController.php');
include_once ('controller/SugerirPreguntaController.php');
include_once('controller/RevisarPreguntaController.php');


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
    public function getLobbyController(){
        return new LobbyController(
            new UsuarioModel($this->getDatabase()),
            new LobbyModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getPerfilController(){
        return new PerfilController(
            new PerfilModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getPartidaController(){
        return new PartidaController(
            new PartidaModel($this->getDatabase()),
            $this->getRenderer());
    }
    public function getSugerirPreguntaController(){
        return new SugerirPreguntaController(
            new SugerirPreguntaModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getRevisarPreguntaController(){
        return new RevisarPreguntaController(
            new RevisarPreguntaModel($this->getDatabase()),
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