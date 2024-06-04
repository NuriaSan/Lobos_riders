<?php
session_start();
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");

// Función para mostrar mensajes de error
function mostrarError($mensaje)
{
    echo '<div style="color: red; text-align: center; font-size: 16px; margin-top: 10px;">' . $mensaje . '</div>';
}

// Función para mostrar mensajes de éxito
function mostrarExito($mensaje)
{
    echo '<div style="color: green; text-align: center; font-size: 36px; margin-top: 10px;">' . $mensaje . '</div>';
}

// Procesamiento de la recuperación de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["dni"]) && isset($_POST["email"])) {
        $dni = $_POST["dni"];
        $email = $_POST["email"];
        $nuevoPassword = $_POST["nuevopassword"];
        $confirmarPasword = $_POST["confirmarpassword"];

        if ($nuevoPassword != $confirmarPasword) {
            mostrarError("Las contraseñas no coinciden. Vuelve a intentarlo.");
            exit();
        }

        $usuarioSesion = Usuario::obtenerUsuarioPorDNI($dni);

        if ($usuarioSesion && $usuarioSesion->getEmail() == $email) {
            $usuarioSesion->actualizarPassword($nuevoPassword);
            session_destroy();
            mostrarExito("Contraseña modificada correctamente. Serás redirigido al login para iniciar sesión");
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 3000);
            </script>";
        } else {
            mostrarError("No se pudo modificar la contraseña. Verifica tu DNI y correo electrónico.");
        }
    } else {
        mostrarError("Error: No se pudo obtener el DNI o correo electrónico.");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modificar Contraseña</title>
    <style>
        body {
            background-image: url("assets/img/Fotos_Lobos/fondo\ nubes\ luna.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            text-align: center;
            margin-top: 200px;

        }

        h2 {
            font-size: 24px;
            color: white;
        }

        form {
            width: 50%;
            margin: auto;
            text-align: center;
            color: white;
        }

        label {
            font-size: 16px;
        }

        input {
            padding: 8px;
            width: 30%;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        button {
            width: 50%;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3282b8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: gray;
        }
    </style>
</head>

<body>
    <h2>Modificar Contraseña</h2>
    <form action="nuevoPassword.php" method="POST">
        <label for="nuevopassword">Nueva Contraseña:</label>
        <input type="password" name="nuevopassword" required><br><br>

        <label for="confirmarpassword">Confirmar Contraseña:</label>
        <input type="password" name="confirmarpassword" required><br><br>

        <input type="hidden" name="dni" value="<?php echo htmlspecialchars($_GET['dni']); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">

        <button type="submit">Cambiar Contraseña</button>
    </form>
</body>

</html>