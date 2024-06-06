<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include ("claseArticulo.php");
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
    <link href="assets/img/favicon.ico" rel="icon">
    <link href="assets/img/favicon-16x16.png" rel="icono 16x16">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500,600,600,700,700i|Poppins:300,300i,400,400i,500,500,600,600,700,700i"
        rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
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
                    <li><a href="eliminarusuario.php" >Eliminar Datos</a></li>
                    <li><a href="modificarusuario.php" >Modificar Usuario</a></li>
                    <li><a href="peticionmerchandising.php">Solicitar Merchandising</a></li>
                    <li><a href="consultapedidosusuario.php">Consultar pedidos</a></li>
                </ul>

            </nav>
            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>
        </div>
    </header>
    <main id="main">
        <div id="contenedor" class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10 m-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt=""
                                    style="max-width: 280px;"></a>
                            <h1>Solicitud de Merchandising</h1>
                        </div>
                        <div class="panel-body">
                            <?php if (isset($_SESSION["usuario"])) {
                                $usuario = $_SESSION["usuario"]->getUsuario();
                            } else {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Debes iniciar sesión para completar la compra.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                            } ?>

                            <div class="row justify-content-center">
                                <div class="col-md-10 m-5">
                                    <div class="card">
                                        <section class="bg-light py-2">
                                            <div class="container my-4">
                                                <div class="row text-center py-3">
                                                    <div class="col-lg-10 m-auto mb-3">
                                                        <h2>Estado de su Orden</h2>
                                                        <p>Su pedido ha sido enviado exitosamente. El ID del pedido es
                                                            <?php echo $_GET['cod_pedido']; ?></p>
                                                    </div>
                                                    <div class="col-lg-10 m-auto m-3">
                                                        <h3>Info:</h3>
                                                        <p>Grácias por interesarte en nuestros diseños, cuando volvamos
                                                            a hacer Merchandising para los miembros del club nos
                                                            pondremos en contacto contigo con los datos que nos has
                                                            proporcionado. </p>
                                                    </div>
                                                </div>
                                                <div id="salir" class="mb-3">
                                                    <button class="btn btn-dark w-100" type="button"
                                                        onclick="volver()">Volver a la Sesión</button>
                                                </div>
                                        </section>
                                        <script>
                                            function volver() {
                                                window.location.href = "sesionusuario.php";
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer id="footer">
        <div class="container py-4">
            <div class="copyright">
                <strong><span>Lobos Riders M.G</span></strong>. Todos los Derechos Reservados.
            </div>
            <div class="credits">
                Diseñado por <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>