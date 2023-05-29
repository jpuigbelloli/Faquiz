<?php

class UsuarioController {

    private $renderer;
    private $usuarioModel;

    public function __construct($usuarioModel,$renderer) {
        $this->usuarioModel = $usuarioModel;
        $this->renderer = $renderer;
    }

    public function listarUsuario() {
        $a = array("a");
        $this->renderer->render('registro', $a);
    }

    public function validarEmail($email){

        filter_var($email,FILTER_VALIDATE_EMAIL);
    }


    public function mailDeValidacion($email){
        $claveValidacion = uniqid();
        $enlaceRedirect = 'https://localhost/index.php?page=autenticacion'.$claveValidacion;
        $destinatario = $email;
        $asunto = "Enlace de Autenticación";
        $mensaje = "Hola.".'<br>'."Por favor, hacé click en el siguiente enlace para autenticarte:".
                    '<br>'. $enlaceRedirect;
        $cabeceras = "From: tuemail@example.com" . "\r\n" .
            "Reply-To: tuemail@example.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        if(mail($destinatario,$asunto,$mensaje,$cabeceras)){
            $this->renderer->render('autenticacion');
        } else{
            echo "No se pudo enviar el mensaje de autenticacion, revise el mail ingresado";
            $this->renderer->render('registro');
        }
    }
    public function registrarUsuario(){
        $this->renderer->render('registro');
        $email = $_POST['email'];

        if(isset($_POST['registrarse'])){
            if($this->validarEmail($email)){
            $this->usuarioModel->registrarse(   $_POST['nombre'],$_POST['apellido'],$_POST['fecha_nac'],
                                                $_POST['genero'], $_POST['ubicacion'],$_POST['email'],$_POST['user_name'],
                                                $_POST['contrasenia'],$_POST['foto_perfil']);
            $this->mailDeValidacion($email);

            }
        }
    }
}