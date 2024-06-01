<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["enviar"])){
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $asunto = htmlspecialchars($_POST["asunto"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    $destinatario = "lobosriders.ilice.mg@gmail.com";
    $subject = "Nuevo mensaje de $nombre";

    $contenido = "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Asunto: $asunto\n";
    $contenido .= "Mensaje:\n$mensaje";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if(mail($destinatario, $subject, $contenido, $headers)){
        echo 'Tu mensaje ha sido enviado. ¡Gracias por tu tiempo!';
    } else {
        echo 'El mensaje no pudo ser enviado. Inténtalo nuevamente.';
    }
}
?>