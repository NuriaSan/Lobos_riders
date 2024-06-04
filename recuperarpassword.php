<?php
// Iniciar una nueva sesión
session_start();
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Recuperar Password</title>

    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="assets/css/style_login.css">
</head>

<body>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        // Validar el usuario y la contraseña
        $usuarioObj = Usuario::nombreClave($usuario, $password);
        $estadoAdmin = Usuario::obtenerAdminPorUsuario($usuario);
        $estadoSocio = Usuario::obtenerSocioPorUsuario($usuario);

        if ($usuarioObj) {
            $_SESSION["autentificado"] = true;
            $_SESSION["usuario"] = $usuarioObj;

            if ($estadoAdmin == 1) {
                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionadmin.php?usuario="; }, 0);</script>';
                exit();
            } elseif ($estadoSocio == 1) {
                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionsocio.php?usuario="; }, 0);</script>';
                exit();
            } else {
                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionusuario.php?usuario="; }, 0);</script>';
                exit();
            }
        } else {
            // Establecer mensaje de error
            echo '<div class="alert alert-danger f" role="alert">Usuario o contraseña incorrectos.</div>';
        }
    }
    ?>
    </div>
    </div>
    </div>

</body>

</html>

<?php

function mostrarError($mensaje)
{
    echo '<div style="color: red; text-align: center; font-size: 16px; margin-top: 10px;">' . $mensaje . '</div>';
}

// Función para mostrar mensajes de éxito
function mostrarExito($mensaje)
{
    echo '<div style="color: green; text-align: center; font-size: 16px; margin-top: 10px;">' . $mensaje . '</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $email = $_POST["email"];

    if ($dni) {
        // Utilizar la clase Usuario para obtener el objeto Usuario correspondiente al DNI
        $usuarioSesion = Usuario::obtenerUsuarioPorDNI($dni);

        // Verificar si se obtuvo un objeto Usuario
        if ($usuarioSesion) {
            // Obtener los valores del objeto Usuario
            $usuario = $usuarioSesion->getUsuario();
            $nombre = $usuarioSesion->getNombre();
            $telefono = $usuarioSesion->getTelefono();
            $email = $usuarioSesion->getEmail();
            $password = $usuarioSesion->getPassword();
        } else {
            // Rsultado en el caso en que no se encuentre el usuario
            echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">No se ha podido obtener el usuario</div>';
            exit();
        }
    } else {
        // Resultado si no se haya obtenido el DNI
        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error: No se pudo obtener el DNI.</div>';
        exit();
    }



    if ($usuarioSesion && $usuarioSesion->getEmail() == $email) {

        //Verificar que el usuario se ha autentificado para controlar la seguridad

        $_SESSION["autentificado"] = true;

        //Acciones en caso de encontrar el usuario y contraseña
        mostrarExito("Usuario encontrado. Puedes restablecer tu contraseña.");
        //Redirigir a la pagina de modificación de contraseña
        echo "<script>window.location.href = 'nuevoPassword.php?dni=" . urlencode($dni) . "&email=" . urlencode($email) . "';</script>";
    } else {
        mostrarError("Usuario no encontrado. Verifica tu DNI y correo electrónico.");
    }
}
?>

<body>

    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-6">
                <div>
                    <h2 class="mb-4" style="color: white;">Obtener Contraseña</h2>
                    <form action="recuperarpassword.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <button type="submit" class="btn btn-orange mb-3">Obtener Contraseña</button>
                        <div id="volver">
                            <button type="button" onclick="volver()" class="btn btn-secondary">
                                Volver Atrás
                            </button>
                        </div>
                        <script>
                            function volver() {
                                window.location.href = "login.php";
                            }
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>


    </div>