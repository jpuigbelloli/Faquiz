<?php
include_once "Configuration.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class RegistroController
{

    private $renderer;
    private $usuarioModel;


    public function __construct($usuarioModel, $renderer)
    {

        $this->usuarioModel = $usuarioModel;
        $this->renderer = $renderer;
    }

    public function list()
    {
        $data["error"] = !empty($_GET["error"]);
        $this->renderer->render('registro', $data);
    }

    public function autent()
    {
        $a = array("a");
        $this->renderer->render('autenticacion', $a);
    }

    public function registrarse()
    {
        if (isset($_POST['registrarse'])) {

            $nombre = $_POST['nombre'] ?? "";
            $apellido = $_POST['apellido'] ?? "";
            $fecha_nac = $_POST['fecha_nac'] ?? "";
            $genero = $_POST['genero'] ?? "";
            $user_name = $_POST['user_name'] ?? "";
            $email = $_POST['email'] ?? "";
            $clave = $_POST['contrasenia'] ?? "";
            $clave_rep = $_POST['contrasenia_rep'] ?? "";
            $imagen_nombre = $_FILES["foto_perfil"]["name"] ?? "";
            $latitud = $_POST['lat'] ?? "";
            $longitud = $_POST['lng'] ?? "";

            if (empty($nombre) || empty($apellido) || empty($fecha_nac) || empty($genero) || empty($user_name) || empty($email) || empty($clave) || empty($clave_rep) || empty($imagen_nombre) || empty($latitud) || empty($longitud)) {
                header('Location:/registro?error=1');
                exit();
            } else {
                $pais = $this->usuarioModel->obtenerPais($latitud, $longitud);
                if(empty($pais) || $pais === 'ERROR-PAIS'){
                    header('Location:/registro?error=ERROR-PAIS');
                    exit();
                }
                $ubicacion = $latitud . ',' . $longitud;
                $token = uniqid();
                if ($this->usuarioModel->validarUsername($user_name) && $this->usuarioModel->validarEmail($email) && $clave === $clave_rep) {
                    $hash = $this->usuarioModel->hashearClave($clave);
                    $ruta_imagen = $this->usuarioModel->validarImagen($imagen_nombre, $user_name);
                    $this->usuarioModel->registrar($nombre, $apellido, $fecha_nac, $genero, $ubicacion, $email, $user_name, $hash, $ruta_imagen, $token, $pais);
                    $rutaQR = QRHelper::generarCodigoQR($user_name);

                    if ($this->enviarEmailRegistro($email, $nombre, $token)) {
                        echo 'Se envió un correo de verificación.';
                    } else {
                        echo 'ERROR.';
                        header('Location:/registro?error=ERROR-EMAIL');
                        exit();
                    }
                    $ruta_imagen = $this->usuarioModel->validarImagen($imagen_nombre, $user_name);
                    header('Location:/autenticacion');
                    exit();
                } else {
                    header('Location:/registro?error=1');
                    exit();
                }
            }
        }
    }

    public function enviarEmailRegistro($email, $nombre, $token)
    {

        // Generar enlace verificacion
        $enlaceVerificacion = 'http://localhost/registro/verificarUsuario?token=' . $token . '&email=' . $email;

        $mailer = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            //$mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $mailer->isSMTP();
            $mailer->Host = 'smtp.gmail.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'faquiz.unlam@gmail.com';
            $mailer->Password = 'jnrkzjytfkxfmcof';
            $mailer->Port = 587;

            // Configuración del remitente y destinatario
            $mailer->setFrom('faquiz.unlam@gmail.com', 'Faquiz');
            $mailer->addAddress($email, $nombre);


            // Contenido del correo
            $mailer->isHTML(true);
            $mailer->Subject = 'Verificacion de Registro en Faquiz';
            $mailer->Body = '<h1>¡Hola ' . $nombre . '!</h1><br> <h3>¡Gracias por registrarte! <br></br> Por favor, haz clic en el siguiente enlace para verificar tu cuenta: <a href="' . $enlaceVerificacion . '">Verificar cuenta</a></h3>';

            if ($mailer->send()) {
                echo 'El correo se envió correctamente.';
            } else {
                echo 'Error al enviar el correo: ' . $mailer->ErrorInfo;
            }

            // Redirigir a una vista de éxito MODIFICARLA POR VISTA "SE ENVIÓ UN CORREO PARA VERIFICAR TU CUENTA"
            header('Location:/autenticacion?mail=OK');
            exit();
        } catch (Exception $e) {
            header('Location:/autenticacion?mail=BAD');
            exit();
        }
    }

    public function autenticacion()
    {
        $codigo = $_GET['verificacion'];

        if ($codigo === "OK") {
            header('Location: /login');
        } else {
            header('Location: /error');
        }
    }

    public function verificarUsuario()
    {

        $tokenCod = $_GET['token'];
        $emailCod = $_GET['email'];
        $token = $tokenCod;
        $email = $emailCod;


        if (empty($token) || empty($email)) {
            header('Location:/registro/autenticacion?verificacion=ERROREMAIL');
            exit();
        } else {
            $bool = $this->usuarioModel->verificarUsuario($token, $email);
            if ($bool) {
                header('Location:/registro/autenticacion?verificacion=OK');
            } else {
                header('Location:/registro/autenticacion?verificacion=ERROREMAIL');
            }

            exit();

        }
    }


}