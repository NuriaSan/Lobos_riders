<?php
include_once ("claseUsuario.php");
session_start();

include_once ("funciones-comunes.php");
include_once ("assets/db/conectar.php");



// Comprobar si el usuario de la sesión está autentificado
if (!isset($_SESSION["usuario"]) || !$_SESSION["autentificado"]) {
  //echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "index.html"; }, 2000);</script>';
  exit();
}
//Obtenemos el dni con la funcion de la clase
if (isset($_SESSION["usuario"])) {
  $usuario = $_SESSION["usuario"];

  // Utilizar la clase Usuario para obtener el DNI del usuario
  $dni = Usuario::obtenerDniPorUsuario($usuario->getUsuario());

  // Verificar si se obtuvo el DNI
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
} else {
  // Resultado si no se ha proporcionado el valor de usuario
  echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Falta el valor de Usuario.</div>';
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Lobos Riders M.G</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.ico" rel="icon">
  <link href="assets/img/favicon-16x16.png" rel="icono 16x16">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <main id="main">
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $dni = $_POST["dni"];
      $usuario = $_POST["usuario"];
      $nombre = $_POST["nombre"];
      $telefono = $_POST["telefono"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $admin = $_POST["admin"];
      $socio = $_POST["socio"];

      function validacion_dni($dni)
      {
        $dni_mayuscula = strtoupper($dni);
        $letra = substr($dni_mayuscula, -1);
        $numeros = substr($dni, 0, -1);

        if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8) {
          return true;
        } else {
          echo '<div class="alert alert-danger text-center" role="alert">DNI no válido.</div>';
          return false;
        }
      }

      function validarTelefono($telefono)
      {
        if (preg_match('/^[0-9]+$/', $telefono)) {
          return true;
        } else {
          return false;
        }
      }

      if (validacion_dni($dni) && validarTelefono($telefono)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (Usuario::registrarUsuario($dni, $usuario, $nombre, $telefono, $email, $hashedPassword, $admin, $socio)) {
          $_POST["dni"] = $dni;
          $_POST["usuario"] = $usuario;
          $_POST["nombre"] = $nombre;
          $_POST["telefono"] = $telefono;
          $_POST["email"] = $email;
          $_POST["password"] = $hashedPassword;
          $_POST["admin"] = $admin;
          $_POST["socio"] = $socio;

          echo '<div class="alert alert-success text-center" role="alert">Usuario registrado con éxito.</div>';
          echo '<meta http-equiv="refresh" content="2;url=mantenimientousuarios.php">';
          exit;
        } else {
          echo '<div class="alert alert-danger text-center" role="alert">No se ha podido registrar el usuario correctamente.</div>';
        }
      }
    }
    ?>

    <section class="inner-page">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card bg-dark text-white mt-5">
              <div class="card-body">
                <h2 class="card-title text-center">Datos de Nuevo Usuario</h2>
                <form method="POST" action="nuevousuarioadmin.php">
                  <div class="mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                  </div>
                  <div class="mb-3">
                    <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
                  </div>
                  <div class="mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                  </div>
                  <div class="mb-3">
                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
                  </div>
                  <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                  </div>
                  <div class="mb-3">
                    <input type="admin" name="admin" class="form-control" placeholder="Admin" required>
                  </div>
                  <div class="mb-3">
                    <input type="socio" name="socio" class="form-control" placeholder="Socio" required>
                  </div>

                  <div class="d-grid">
                    <button type="submit" class="body-btn">Registrar Usuario</button>
                  </div>
                </form>
                <div class="col-md-12">
                  <div id="datosmostrados">

                    <div id="espacioadministrador" class="mb-3">
                      <button class="btn btn-secondary w-100" type="button" onclick="mantenimientoUsuarios()">Gestión de
                        Usuarios</button>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                //Uso de funciones para redirigir a diferentes formularios con botones

                function mantenimientoUsuarios() {
                  window.location.href = "mantenimientousuarios.php?dni=";
                }
              </script>
            </div>
          </div>
    </section>

  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3>Lobos Riders M.G<span>.</span></h3>
              <p>
                Partida Algorós, 1107, <br>
                03293 Elche, Alicante<br><br>
                <strong>Phone:</strong> +34 666 66 66 66<br>
                <strong>Email:</strong> lobosriders.ilice.mg@gmail.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="https://x.com/LobosRiders" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="https://es-es.facebook.com/lobosridersilice/" class="facebook"><i
                    class="bx bxl-facebook"></i></a>
                <a href="https://www.instagram.com/loborides" class="instagram"><i class="bx bxl-instagram"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Links de Acceso</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="index.html">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="quienes_somos.html">Quienes Somos</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="miembros.html">Miembros</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="aniversarios.html">Aniversarios</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Info Section</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="terminosycondiciones.html">Terminos y condiciones</a>
              </li>
              <li><i class="bx bx-chevron-right"></i> <a href="politicadeprivacidad.html">Politica de Privacidad</a>
              </li>
              <li><i class="bx bx-chevron-right"></i> <a href="contacto.html">contacto</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="login.php">Log In</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Lobos Riders Newsletter</h4>
            <p>Si quieres estar al día de las novedades. Suscríbete.</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Suscríbete">
            </form>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Lobos Riders M.G.</span></strong>. Todos los Derechos Reservados
      </div>
      <div class="credits">
        Diseñado por <a href="https://diseñosdeNuriaSan.com/">Nuria Sánchez</a>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>