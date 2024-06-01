<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Poppins" rel="stylesheet">
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

    <header id="header" class="fixed-top">
        <div class="container bg-black d-flex align-items-center justify-content-lg-between">
            <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt=""
                    class="img-fluid"></a>
            <h1 class="logo me-auto me-lg-0"><a href="index.html"><span>L</span>obos <span>R</span>iders
                    M.G<span>.</span></a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a href="eliminarusuario.php">Eliminar Datos</a></li>
                    <li><a href="modificarusuario.php">Modificar Usuario</a></li>
                </ul>
            </nav>
            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>
        </div>
    </header>

    <?php
    $nombre = "";

    if (isset($_SESSION["usuario"])) {
        $sesionAdmin = $_SESSION["usuario"];
    }

    if (isset($_GET["dni"])) {
        $dni = $_GET["dni"];
        $usuariofila = Usuario::obtenerUsuarioPorDNI($dni);

        if ($usuariofila) {
            $dniusu = $usuariofila->getDni();
            $usuario = $usuariofila->getUsuario();
            $nombre = $usuariofila->getNombre();
            $telefono = $usuariofila->getTelefono();
            $email = $usuariofila->getEmail();
            $password = $usuariofila->getPassword();
        } else {
            echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">No se ha podido obtener el usuario</div>';
            exit();
        }
    } else {
        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error: No se pudo obtener el DNI del Cliente.</div>';
    }

    ?>
    <div id="contenedor" class="container text-center m-5">
        <div class="row">
            <div class="col-md-12 text-center m-5">
                <h1 style="color: purple;">Esta intentando eliminar al Usuario: <?php echo $nombre; ?></h1>
                <p>Si confirma la eliminación del usuario perderá todos sus datos</p>
            </div>
        </div>
        <form id="confirmacionForm" method="POST" action="">
            <input type="hidden" name="dni" value="<?php echo $dniusu; ?>">
            <button type="button" id="confirmarEliminar" class="btn btn-danger">Confirmar Eliminación</button>
        </form>
    </div>

    <?php

    if (isset($_POST['dni'])) {
        // Procesa la eliminación del usuario
        if (isset($_POST['dni'])) {
            $dniusu = $_POST['dni'];
            Usuario::eliminarUsuario($dniusu);
            echo '<div class="alert alert-success text-center bg-success text-white" role="alert">Usuario eliminado correctamente</div>';
            // Redirige a la página de mantenimiento de usuarios después de la eliminación
            echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "mantenimientousuarios.php?mensaje=eliminado"; }, 3000);</script>';
            exit();
        } else {
            echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error: No se pudo obtener el DNI del Cliente.</div>';
            exit();
        }
    }
    ?>

    <script>
        document.getElementById('confirmarEliminar').addEventListener('click', function () {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario? Si acepta no podrá recuperar los datos.')) {
                document.getElementById('confirmacionForm').submit();
            }
        });
    </script>




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
                                <a href="https://www.instagram.com/loborides" class="instagram"><i
                                        class="bx bxl-instagram"></i></a>
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
                            <li><i class="bx bx-chevron-right"></i> <a href="terminosycondiciones.html">Terminos y
                                    condiciones</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="politicadeprivacidad.html">Politica de
                                    Privacidad</a></li>
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