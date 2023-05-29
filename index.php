<?php
include_once('Configuration.php');
$configuration = new Configuration();
$db=$configuration->getDatabase();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'inicioSinLog';
$method = $_GET['action'] ?? 'redirigir';

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

