<?php
session_start();
include_once ("funciones-comunes.php");
include_once ("assets/db/conectar.php");
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

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center justify-content-lg-between">

            <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt=""
                    class="img-fluid"></a>
            <h1 class="logo me-auto me-lg-0"><a href="index.html"><span>L</span>obos <span>R</span>iders
                    M.G<span>.</span></a>
            </h1>

            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto active" href="index.html">Home</a></li>
                    <li><a class="nav-link scrollto" href="quienes_somos.html">Quienes Somos</a></li>
                    <li><a class="nav-link scrollto " href="merchandising.html">Merchandising</a></li>
                    <li><a class="nav-link scrollto" href="miembros.html">Miembros</a></li>
                    <li class="dropdown"><a href="eventos.html"><span>Eventos</span> <i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="aperturas.html">Aperturas</a></li>
                            <li class="dropdown"><a href="aniversarios.html"><span>Aniversarios</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="22_aniversario.html">XXII Aniversario</a></li>
                                    <li><a href="23_aniversario.html">XXIII Aniversario</a></li>
                                    <li><a href="24_aniversario.html">XXIV Aniversario</a></li>
                                    <li><a href="26_aniversario.html">XXVI Aniversario</a></li>
                                    <li><a href="27_aniversario.html">XXVII Aniversario</a></li>
                                    <li><a href="28_aniversario.html">XXVIII Aniversario</a></li>
                                    <li><a href="30_aniversario.html">XXX Aniversario</a></li>
                                    <li><a href="32_aniversario.html">XXXII Aniversario</a></li>
                                </ul>
                            </li>
                            <li><a href="rutas.html">Rutas</a></li>
                            <li><a href="video.html">Video Promocional</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link scrollto" href="contacto.html">Contacto</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <!-- .navbar -->

            <a href="login.php" class="get-started-btn scrollto">Log In</a>

        </div>
    </header>
    <!-- End Header -->


    <!-- ======= Cabecera Section ======= -->
    <section id="cabecera" class="d-flex align-items-center justify-content-center">
        <div class="container" data-aos="fade-up">

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
                <div class="col-xl-6 col-lg-8">
                    <h1><span>L</span>obos <span>R</span>iders<span>.</span></h1>
                    <h2>Registro</h2>
                </div>
            </div>

        </div>
    </section>
    <!-- End Cabecera -->

    <main id="main">
    <?php
include ("claseUsuario.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $admin = $_POST["admin"];
    $edit = $_POST["edit"];

    function validacion_dni($dni) {
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

    function validarTelefono($telefono) {
        if (preg_match('/^[0-9]+$/', $telefono)) {
            return true;
        } else {
            return false;
        }
    }

    if (validacion_dni($dni) && validarTelefono($telefono)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (Usuario::registrarUsuario($dni, $usuario, $nombre, $telefono, $email, $hashedPassword, $admin, $edit)) {
            $_SESSION["dni"] = $dni;
            $_SESSION["usuario"] = $usuario;
            $_SESSION["nombre"] = $nombre;
            $_SESSION["telefono"] = $telefono;
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $hashedPassword;
            $_SESSION["admin"] = $admin;
            $_SESSION["edit"] = $edit;

            echo '<div class="alert alert-success text-center" role="alert">Usuario registrado con éxito.</div>';
            echo '<meta http-equiv="refresh" content="2;url=login.php">';
            exit;
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">No se ha podido registrar el usuario correctamente.</div>';
        }
    }
}
?>

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Registro de Usuario</h2>
                    <ol>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li>Registro de Nuevo Usuario</li>
                    </ol>
                </div>

            </div>
        </section><!-- End Breadcrumbs -->


<section class="inner-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark text-white mt-5">
                    <div class="card-body">
                        <h2 class="card-title text-center">Datos de Nuevo Usuario</h2>
                        <form method="POST" action="nuevousuario.php">
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
                            <input type="hidden" name="admin" value="0">
                            <input type="hidden" name="edit" value="0">
                            <div class="d-grid">
                                <button type="submit" class="body-btn" >Registrar Usuario</button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="text-orange">Atrás</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                <a href="https://es-es.facebook.com/lobosridersilice/" class="facebook"><i class="bx bxl-facebook"></i></a>
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
              <li><i class="bx bx-chevron-right"></i> <a href="terminosycondiciones.html">Terminos y condiciones</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="politicadeprivacidad.html">Politica de Privacidad</a></li>
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