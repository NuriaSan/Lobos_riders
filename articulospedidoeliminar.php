<?php
include_once("assets/db/conectar.php");
include("claseUsuario.php");
include_once("claseArticulo.php");
session_start();
include_once("funciones-comunes.php");
include("seguridad.php");

$mensajeExito = isset($_GET['mensaje']) && $_GET['mensaje'] === 'exito';
$mensajeEliminado = isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado';
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Poppins" rel="stylesheet">
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
            <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt="" class="img-fluid"></a>
            <h1 class="logo me-auto me-lg-0"><a href="index.html"><span>L</span>obos <span>R</span>iders M.G<span>.</span></a></h1>
            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>
        </div>
    </header>

    <main id="main">
        <div id="contenedor" class="container">
            <div class="row justify-content-center m-5">
                <div class="col-md-10 m-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt="" style="max-width: 280px;"></a>
                            <h4>Tienes privilegios de Administrador</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">Estas son las acciones disponibles:</h2>
                            <?php
                            if (isset($_GET["cod_articulo"])) {
                                $cod_articulo = $_GET["cod_articulo"];
                                $cod_articulospedido = $_GET["cod_articulospedido"];
                                
                                $articulo = Articulo::obtenerArticuloPorCodigo($cod_articulo);
                                #echo $cod_articulo;
                                if ($articulo) {
                                    echo "<body style='text-align:center; display:block; justify-content: center; align-items: center;'>";
                                    echo "<h2 style='font-size: 35px;'>Confirmar Eliminación</h2>";
                                    echo "<p style= 'font-size:20px;'>¿Estás seguro de que deseas eliminar el articulo?</p>";
                                    echo "<div style='background-color:#3282b8'>";
                                    echo "<table border='0' style= 'width:100%; background-color:#3282b8; align-items: center; text-align:center;'>";
                                    echo "<tr>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Codigo</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Codigo Articulo</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Nombre</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Descripción</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Categoria</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Precio</th>
                                            <th style='background-color:grey; font-size: 16px; height: 30px;'>Imagen Ruta</th>
                                            <th style='background-color:grey; font-size: 16px; '>Imagen</th>
                                        </tr>";
                                    echo "<tr>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$cod_articulospedido}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$cod_articulo}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$articulo->getNombre()}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$articulo->getDescripcion()}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$articulo->getCategoria()}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; height: 30px;'>{$articulo->getPrecio()}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; '>{$articulo->getImagen()}</td>";
                                    echo "<td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>
                                            <img src='" . $articulo->getImagen() . "' alt='imagen' style='width: 250px; height: 100px; margin:0; padding: 0;'>";
                                    echo "</tr>";
                                    echo "</table>";
                                    echo "</div>";
                                    echo "</body>";
                                    echo "<br>";
                                    echo "<form action='confirmar_eliminacion_admin.php' method='post'>";
                                    echo "<input type='hidden' name='cod_articulospedido' value='{$cod_articulospedido}'>";
                                    echo "<input type='submit' name='confirmar' style='font-family:Courier New, Courier, monospace;font-size: 18px; height: 40px; width: 250px; margin-right: 20px; border-radius: 8px; background-color: orange; border: none;' value='Sí, Eliminar'>";
                                    echo "<button type='button' style='font-family:Courier New, Courier, monospace; font-size: 18px; height: 40px; width: 250px; border-radius: 8px; background-color: orange; border:none;' onclick='history.back()'>No, Volver Atrás</button>";
                                    echo "</form>";
                                    echo "<br>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
