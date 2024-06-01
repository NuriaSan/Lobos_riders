<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la clave 'autentificado' está definida en la sesión y es true
if (!isset($_SESSION['autentificado'])) {
    echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "index.html"; }, 2000);</script>';
    exit();
}
?>
