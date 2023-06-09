<?php
include_once "Configuration.php";

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

    /*public function hashearClave($clave)
    {
        return password_hash($clave, PASSWORD_DEFAULT);
    }

    public function validarEmail($email){
        return filter_var($email,FILTER_VALIDATE_EMAIL);
    }*/

    public function mailDeValidacion($email)
    {   include_once 'Faquiz/mail.php';

        $this->autent();
//        $claveValidacion = uniqid();
//        $enlaceRedirect = 'https://localhost/index.php?page=login' . $claveValidacion;
//        $destinatario = $email;
//        $asunto = "Enlace de Autenticación";
//        $mensaje = "Bienvenido a Faquiz!!" . '<br>' . "Por favor, hacé click en el siguiente enlace para
//                    autenticar tu cuenta:" . '<br>' . $enlaceRedirect;
//        $cabeceras = "From: flgalvan@alumno.unlam.edu.ar" . "\r\n" .
//            "Reply-To: flgalvan@alumno.unlam.edu.ar" . "\r\n" .
//            "X-Mailer: PHP/" . phpversion();
//
//        if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
//            $this->renderer->render('autenticacion');
//        } else {
//            echo "No se pudo enviar el mensaje de autenticacion, revise el email ingresado";
//            $this->renderer->render('registro');
//        }
    }

   /* function verificarImagen($imagen, $nombre){
        $uploadOk = 1;
        $target_dir = "imgsPerfil/";
        $target_file = $target_dir . basename($imagen['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_file_name = $target_dir . $nombre . '.' . $imageFileType;

        $extencionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($imageFileType, $extencionesPermitidas)) {
            echo "Solo se permiten imágenes en formato JPG, JPEG, PNG o GIF. ";
            $uploadOk = 0;
        }

        if (file_exists($new_file_name)) {
            echo "Lo siento, ya hay una imagen cargada con ese nombre de archivo. ";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Intente nuevamente más tarde. ";
            exit();
        } else {
            if (move_uploaded_file($imagen['tmp_name'], $new_file_name)) {
                $this->usuarioModel->actualizarNombreImg($nombre,$imageFileType);
                echo "Carga exitosa";
//              header("Location: index.php");

            } else {
                echo "Lo siento, ha ocurrido un error, intente nuevamente más tarde. ";
            }
        }

    }*/
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

            if ($this->usuarioModel->validarUsername($user_name) && $this->usuarioModel->validarEmail($email) && $clave === $clave_rep) {
                $hash = $this->usuarioModel->hashearClave($clave);
                $ruta_imagen = $this->usuarioModel->validarImagen($imagen_nombre,$user_name);
                $this->usuarioModel->registrar($nombre, $apellido, $fecha_nac, $genero, $email, $user_name, $hash,$ruta_imagen);
                header('Location:/autenticacion');
                exit();
            }else{
                header('Location:/registro?error=1');
                exit();
            }
        }
//            if (($this->validarEmail($email))) {
//
//                if($this->validarUsername())
//                if ($clave === $clave_rep) {
//                    $hash = $this->hashearClave($clave);
//                    if ($hash) {
//                        $nombre = $_POST['nombre'];
//                        $apellido = $_POST['apellido'];
//                        $fecha_nac = $_POST['fecha_nac'];
//                        $genero = $_POST['genero'];
////                        $ubicacion = $_POST['ubicacion'];
//                        $user_name = $_POST['user_name'];
//                    $target_dir = "imgs/";
//                    $rutaImagen = $target_dir . basename($_FILES['imagen']['user_name']);
//                    $foto_perfil    = $_FILES['foto_perfil'];
////                    $data['usuario'] =$this->usuarioModel->registrarse($nombre,$apellido,$fecha_nac,
////                        $genero, $ubicacion , $email,$user_name,
////                        $clave, $rutaImagen);
////                    $this->verificarImagen($foto_perfil,$user_name);
//                        $this->mailDeValidacion($email);
//                        $this->renderer->render('autenticacion');
//                    } else {
//                        die ('Ups no inserto nada');
//                    }
//                } else {
//                    return "las contraseñas no coinciden";
//                }
//
//            }
//        }
//        $this->renderer->render('registro');
//    }
    }
}