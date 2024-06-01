<?php
    session_start();

    // Verificar si hay una sesión abierta
    if(isset($_SESSION['usuario'])) {
        // Si hay una sesión abierta, se muestra el mensaje
        $_SESSION = array(); 
        session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desconexión Exitosa</title>
    <meta http-equiv="refresh" content="3;url=login.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #mensaje {
            color: green;
            text-align: center;
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div id="mensaje">
        Cerrando Sesión. Serás redirigido a la página de inicio de sesión en 3 segundos.
    </div>
</body>
</html>
<?php
    } else {
        // Si no hay una sesión abierta, se redirige directamente
        echo '<script>window.location.href = "login.php";</script>';
        exit; 
    }
?>
