<?php

$to = 'florenciagalvan34@hotmail.com';
$subject = 'Prueba de servidor SMTP';
$message = 'Este es un mensaje de prueba enviado desde el servidor SMTP.';
$headers = 'From: ngciclon@gmail.com';

if (mail($to, $subject, $message, $headers)) {
    echo 'El correo se envió correctamente.';
} else {
    echo 'No se pudo enviar el correo. Verifica la configuración del servidor SMTP.';
}
