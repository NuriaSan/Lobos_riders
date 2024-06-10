<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
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
                    <li><a href="eliminarusuario.php" >Eliminar Datos</a></li>
                    <li><a href="modificarusuario.php" >Modificar Usuario</a></li>
                    <li><a href="peticionmerchandisingusuario.php">Solicitar Merchandising</a></li>
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
                        <section class="bg-light py-2">
                            <div class="container my-4">
                                <div class="row text-center py-3">
                                    <div class="col-lg-6 m-auto mb-3">
                                        <h3>Lobos Riders</h3>
                                        <p>Como sabes no somos una tienda, pero sabemos que nuestros amigos están
                                            encantados de llevar nuestros diseños.</p>
                                        <p>Así que si estás interesado puedes hacer tu solicitud y nos pondremos en
                                            contacto contigo cuando hagamos camisetas para nosotros. ¡Eres como de la
                                            familia!</p>
                                    </div>

                                </div>
                            </div>

                        </section>
                        <div class="row">
                            <div class="col-lg-6 m-auto">
                                <form method="GET" action="">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <label for="sort">Ordenar por:</label>
                                            <select class="form-control mb-2" id="sort" name="sort"
                                                onchange="this.form.submit()">
                                                <option value="nombre_asc" <?php echo ($sort == 'nombre_asc') ? 'selected' : ''; ?>>Nombre (A-Z)</option>
                                                <option value="nombre_desc" <?php echo ($sort == 'nombre_desc') ? 'selected' : ''; ?>>Nombre (Z-A)</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label for="busqueda">Buscar:</label>
                                            <input type="text" class="form-control mb-2" id="busqueda" name="busqueda"
                                                value="<?php echo $busqueda; ?>" placeholder="Buscar...">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-dark mb-2">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="container py-5">
                            <div class="row justify-content-md-center">
                                <div class="col-lg-10">
                                    <div class="row" id="resultados">
                                        <?php
                                        include ("assets/db/conectar.php");
                                        $conn = conectar_DB();
                                        if (!$conn) {
                                            die("Error en la conexión a la base de datos");
                                        }

                                        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'nombre_asc';
                                        $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

                                        switch ($sort) {
                                            case 'nombre_asc':
                                                $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda ORDER BY nombre ASC";
                                                break;
                                            case 'nombre_desc':
                                                $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda ORDER BY nombre DESC";
                                                break;
                                            default:
                                                $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda";
                                        }

                                        $stmt = $conn->prepare($consulta);
                                        $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
                                        $stmt->execute();

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $cod_articulo = $row['cod_articulo'];
                                            $nombre = $row['nombre'];
                                            $precio = $row['precio'];
                                            $descripcion = $row['descripcion'];
                                            $categoria = $row['categoria'];
                                            $imagen = $row['imagen'];
                                            echo '<div class="col-md-4 mb-4">';
                                            echo '  <div class="card mb-4 h-100 rounded-3">';
                                            echo '    <img src="' . $imagen . '" class="card-img-top rounded-top mt-3" alt="Product Image" style="height: 200px; object-fit: cover;">';
                                            echo '    <div class="card-body d-flex flex-column">';
                                            echo '      <div>';
                                            echo '        <h5 class="card-title text-center">' . $nombre . '</h5>';
                                            echo '        <h2 class="text-center mb-0"><b>' . $precio . ' €</b></h2>';
                                            echo '      </div>';
                                            echo '      <br>';
                                            echo '      <p class="text-muted">' . $descripcion . '</p>';
                                            echo '      <div class="mt-auto">';
                                            echo '        <div class="d-grid gap-2 m-2">';
                                            echo '          <a href="articuloind.php?cod_articulo=' . $cod_articulo . '" class="btn btn-dark"><i class="far fa-eye"></i>Detalle de Articulo</a>';
                                            echo '        </div>';
                                            echo '      </div>';
                                            echo '    </div>';
                                            echo '  </div>';
                                            echo '</div>';
                                            
                                        }
                                        ?>
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