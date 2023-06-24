<?php
include_once('Configuration.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');
$configuration = new Configuration();
$db=$configuration->getDatabase();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'getInicioSinLogController';
$method = $_GET['action'] ?? 'list';

$router->route($module, $method);





/*$page=$_GET['page']??'inicioSinLog';

switch ($page){

    case 'iniciarSesion':
        $songscontoller = $configuration->getSongsController();
        $songscontoller->list();
        break;
    case 'registro':
        $registroController = $configuration->getUsuarioController();
        $registroController->registrarUsuario();
        break;
    default:
        $inicioSinLogController = $configuration->getInicioSinLogController();
        $inicioSinLogController->redirigir();
}*/

