<?php
include_once "Configuration.php";
class RegistroController {

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

    public function hashearClave($clave){
        return $claveHasheada = password_hash($clave,PASSWORD_DEFAULT);
    }
    public function validarEmail($email){

    return $email= filter_var($email,FILTER_VALIDATE_EMAIL);
    }
    public function mailDeValidacion($email){
        $claveValidacion = uniqid();
        $enlaceRedirect = 'https://localhost/index.php?page=login'.$claveValidacion;
        $destinatario = $email;
        $asunto = "Enlace de Autenticación";
        $mensaje = "Bienvenido a Faquiz!!".'<br>'."Por favor, hacé click en el siguiente enlace para 
                    autenticar tu cuenta:".'<br>'. $enlaceRedirect;
        $cabeceras = "From: flgalvan@alumno.unlam.edu.ar" . "\r\n" .
                    "Reply-To: flgalvan@alumno.unlam.edu.ar" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

        if(mail($destinatario,$asunto,$mensaje,$cabeceras)){
            $this->renderer->render('autenticacion');
        } else{
            echo "No se pudo enviar el mensaje de autenticacion, revise el email ingresado";
            $this->renderer->render('registro');
        }
    }

    function verificarImagen($imagen, $nombre){
        $uploadOk = 1;
        $target_dir = "imgs/";
        $target_file = $target_dir . basename($_FILES['imagen']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_file_name = $target_dir . $nombre . '.' . $imageFileType;

        if (file_exists($new_file_name)) {
            echo "Lo siento, ya hay una imagen cargada con ese nombre de archivo. ";
            $uploadOk = 0;
        }

        if ($_FILES['imagen']['size'] > 500000) {
            echo "Lo siento, la imagen seleccionada es demasiado grande. ";
            $uploadOk = 0;
        }

        if ($imageFileType != 'png') {
            echo "Solo se permiten imágenes en formato .PNG. ";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Intente nuevamente más tarde. ";
            exit();
        } else {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $new_file_name)) {
                echo "Carga exitosa";
//            echo "El archivo " . htmlspecialchars(basename($_FILES["imagen"]["name"])) . " ha sido subido con el nombre " . basename($new_file_name) . ".";
                //header("Location: index.php");
                //exit;
            } else {
                echo "Lo siento, ha ocurrido un error, intente nuevamente más tarde. ";
            }
        }

    }
    public function registrarse(){
        if(isset($_POST['registrarse'])){
        $email = $_POST['email'];

            if(!($this->validarEmail($email))) {
                $clave = $_POST['contrasenia'];
                if($this->hashearClave($clave)){
                    $nombre         = $_POST['nombre'];
                    $apellido       = $_POST['apellido'];
                    $fecha_nac      = $_POST['fecha_nac'];
                    $genero         = $_POST['genero'];
                    $ubicacion      = $_POST['ubicacion'];
                    $user_name      = $_POST['user_name'];
                    $target_dir = "imgs/";
                    $rutaImagen = $target_dir . basename($_FILES['imagen']['user_name']);
                    $foto_perfil    = $_FILES['foto_perfil'];
                    $data['usuario'] =$this->usuarioModel->registrarse($nombre,$apellido,$fecha_nac,
                        $genero, $ubicacion , $email,$user_name,
                        $clave, $rutaImagen);
                    $this->verificarImagen($foto_perfil,$user_name);
                    $this->mailDeValidacion($email);
                    $this->renderer->render('autenticacion');
                } else {
                    die ('Ups no inserto nada');
}
            }
        } $this->renderer->render('registro');
    }
}