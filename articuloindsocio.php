<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'nombre_asc';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

if (isset($_SESSION['carrito'])) {
    $total_articulos = count($_SESSION['carrito']);
    $precio_total = 0;
} else {
    $_SESSION['carrito'] = array();
    $titulo_carrito = "(0)";
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
                    <li><a href="modificarusuariosocio.php">Modificar Usuario</a></li>
                    <li><a href="peticionmerchandisingsocio.php">Solicitar Merchandising</a></li>
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
                        <section class="bg-light py-2">
                            <div class="container my-4">
                                <div class="row text-center py-3">
                                    <div class="col-lg-6 m-auto mb-3">
                                        <h3>Lobos Riders</h3>
                                        <p>Merchandising de Socios</p>

                                    </div>

                                </div>
                            </div>

                        </section>
                        <div class="row">
                            <?php

                            // Obtiene el articulo a modificar desde la URL
                            if (isset($_GET["cod_articulo"])) {
                                $codigo_articulo = $_GET["cod_articulo"];

                                $articulo = Articulo::obtenerArticuloPorCodigo($codigo_articulo);

                                // Verifica si se obtuvo un objeto Articulo
                                if ($articulo) {
                                    // Obtiene los valores del objeto Articulo
                                    $codigo_articulo = $articulo->getCod_articulo();
                                    $nombre = $articulo->getNombre();
                                    $precio = $articulo->getPrecio();
                                    $descripcion = $articulo->getDescripcion();
                                    $categoria = $articulo->getCategoria();
                                    $imagen = $articulo->getImagen();

                                } else {
                                    // Maneja el caso en que no se encuentre el artículo
                                    echo "Error: No se pudo obtener el artículo.";
                                    exit();
                                }
                            } else {
                                // Maneja el caso en que no se haya proporcionado un valor de artículo en la URL
                                echo "Error: Falta el valor de artículo.";
                                exit();
                            }

                            ?>

                            <section class="bg-light">
                                <div class="container">
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col-lg-4 mt-5 d-flex">
                                            <div class="card h-100">
                                                <img class="card-img img-fluid" src="<?php echo "" . $imagen; ?>"
                                                    alt="Card image cap" id="product-detail">
                                            </div>
                                        </div>
                                        <div class="col-lg-7 mt-5 d-flex">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <!-- Contenido especifico del Articulo seleccionado -->
                                                    <?php
                                                    // Mostrar resultados detallados
                                                    if (isset($_GET["cod_articulo"])) {
                                                        $codigo_articulo = $_GET["cod_articulo"];
                                                    }
                                                    echo '<h1 class="h2">' . $nombre . '</h1>';
                                                    echo '<p class="h3 py-2">Precio €' . $precio . '</p>';
                                                    echo '<p class="py-2"></p>';
                                                    echo '<ul class="list-inline">';
                                                    echo '<li class="list-inline-item"><h6>Categoria:</h6></li>';
                                                    echo '<li class="list-inline-item"><p class="text-muted"><strong>' . $categoria . '</strong></p></li>';
                                                    echo '</ul>';
                                                    echo '<h6>Descripción:</h6>';
                                                    echo '<p>' . $descripcion . '</p>';
                                                    echo '<ul class="list-inline">';
                                                    echo '<li class="list-inline-item"><h6>Código :</h6></li>';
                                                    echo '<li class="list-inline-item"><p class="text-muted"><strong>' . $codigo_articulo . '</strong></p></li>';
                                                    echo '</ul>';

                                                    echo '<form id="formulario_articulo" name="formulario_articulo" method="GET" action="vercarta.php">';
                                                    echo '<input type="hidden" name="cod_articulo" value="' . $codigo_articulo . '">';
                                                    echo '<input type="hidden" name="cantidad" value="1">';

                                                    $disableOptions = ($codigo_articulo === 'aaa00008' || $codigo_articulo === 'aaa00007');

                                                    echo '<div class="row">';
                                                    echo '<div class="col-auto">';
                                                    echo '<label for="talla">Talla:</label>';
                                                    echo '<select id="talla" name="talla" class="form-control">';
                                                    if ($disableOptions) {
                                                        echo '<option value="--">--</option>';
                                                    } else {
                                                        echo '<option value="XS">XS</option>';
                                                        echo '<option value="S">S</option>';
                                                        echo '<option value="M">M</option>';
                                                        echo '<option value="L">L</option>';
                                                        echo '<option value="XL">XL</option>';
                                                        echo '<option value="XXL">XXL</option>';
                                                    }
                                                    echo '</select>';
                                                    echo '</div>';
                                                    echo '<div class="col-auto">';
                                                    echo '<label for="genero">Género:</label>';
                                                    echo '<select id="genero" name="genero" class="form-control">';
                                                    if ($disableOptions) {
                                                        echo '<option value="--">--</option>';
                                                    } else {
                                                        echo '<option value="hombre">Hombre</option>';
                                                        echo '<option value="mujer">Mujer</option>';
                                                    }
                                                    echo '</select>';
                                                    echo '</div>';
                                                    echo '<div class="col-auto">';
                                                    echo '<label for="tipo_prenda">Tipo de Prenda:</label>';
                                                    echo '<select id="tipo_prenda" name="tipo_prenda" class="form-control">';
                                                    if ($disableOptions) {
                                                        echo '<option value="--">--</option>';
                                                    } else {
                                                        echo '<option value="manga_corta">Manga Corta</option>';
                                                        echo '<option value="tirantes">Tirantes</option>';
                                                    }
                                                    echo '</select>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<div class="row pb-3">';
                                                    echo '<div class="col d-grid">';
                                                    echo '<button type="submit" class="btn btn-dark mt-3" name="submit">Añadir al Carrito</button>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</form>';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
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

    <script>
        document.querySelector('form#formulario_articulo').addEventListener('submit', function () {
            document.getElementById('talla_hidden').value = document.getElementById('talla').value;
            document.getElementById('genero_hidden').value = document.getElementById('genero').value;
            document.getElementById('tipo_prenda_hidden').value = document.getElementById('tipo_prenda').value;
        });
    </script>

    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>