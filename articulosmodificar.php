<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");

// Comprobar si está autentificado
if (!isset($_SESSION["usuario"]) || !$_SESSION["autentificado"]) {
    header('Location: Pagina-Index.php');
    exit();
}

// Obtenemos el usuario de la sesión
$usuario = $_SESSION["usuario"] ?? null;

// Inicializar variables
$mensaje = "";
$cod_articulo = $_GET['cod_articulo'] ?? null;
$nombre = $precio = $descripcion = $categoria = $imagenActual = "";

if ($cod_articulo) {
    $conn = conectar_DB();
    if (!$conn) {
        die("Error al conectar a la base de datos");
    }

    $articulo = Articulo::obtenerArticuloPorCodigo($cod_articulo);

    if ($articulo) {
        $nombre = $articulo->getNombre();
        $precio = $articulo->getPrecio();
        $descripcion = $articulo->getDescripcion();
        $categoria = $articulo->getCategoria();
        $imagenActual = $articulo->getImagen();
    } else {
        $mensaje = "Error: No se pudo obtener el artículo.";
    }
} else {
    $mensaje = "Error: Falta el valor de artículo.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_articulo = $_POST["cod_articulo"];
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"];
    $imagenActual = $_POST["imagen"];

    // Comprobar si se ha introducido una imagen nueva
    if (isset($_FILES["nuevaImagen"]) && $_FILES["nuevaImagen"]["name"]) {
        $directorioImagenes = "articulosImg/";
        $nombreImagen = basename($_FILES["nuevaImagen"]["name"]);
        $rutaNuevaImagen = $directorioImagenes . $nombreImagen;

        move_uploaded_file($_FILES["nuevaImagen"]["tmp_name"], $rutaNuevaImagen);
        $imagen = $rutaNuevaImagen;

        // Elimina la imagen anterior si existe
        if ($imagenActual && file_exists($imagenActual)) {
            unlink($imagenActual);
        }
    } else {
        $imagen = $imagenActual;
    }

    $resultado = Articulo::modificarArticulo($cod_articulo, $nombre, $precio, $descripcion, $categoria, $imagen);

    header("Location: articulosmodificar.php?cod_articulo=$cod_articulo&mensaje=exito");
    exit();

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Lobos Riders M.G</title>
    <link href="assets/img/favicon.ico" rel="icon">
    <link href="assets/img/favicon-16x16.png" rel="icono 16x16">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500,600,600i,700,700i"
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
            <nav id="navbar" class="navbar order-last order-lg-0"></nav>
            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>
        </div>
    </header>

    <main id="main">
        <div id="contenedor" class="container mt-5">
            <?php if ($mensaje): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="row justify-content-center">
                <div class="col-md-8 mb-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt=""
                                    style="max-width: 280px;"></a>
                            <h1>Bienvenido a tu Espacio</h1>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">Estos son los datos que puedes modificar:</h2>

                            <div id="contenedor" style="width: 100%">
                                <div class="formulario"
                                    style="width: 80%; margin: auto; padding: 20px 30px; background-color:orange; text-align: left; border-radius: 5px;">
                                    <form method="POST"
                                        action="articulosmodificar.php?cod_articulo=<?php echo htmlspecialchars($cod_articulo); ?>"
                                        enctype="multipart/form-data">
                                        <label for="codigo">Codigo:</label>
                                        <input type="text" name="cod_articulo"
                                            style="font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"
                                            value="<?php echo htmlspecialchars($cod_articulo); ?>" readonly><br><br>

                                        <label for="nombre">Nombre:</label>
                                        <input type="text" name="nombre" maxlength="40" size="40"
                                            style="border: none; font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"
                                            value="<?php echo htmlspecialchars($nombre); ?>"><br><br>

                                        <label for="precio">Precio:</label>
                                        <input type="number" name="precio" maxlength="15" size="15" step="0.01" min="0"
                                            max="999999999999.99"
                                            style="border: none; font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"
                                            value="<?php echo htmlspecialchars($precio); ?>"><br><br>

                                        <label for="descripcion">Descripción:</label>
                                        <textarea name="descripcion" maxlength="255"
                                            style="border: none; font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"><?php echo htmlspecialchars($descripcion); ?></textarea><br><br>

                                        <label for="categoria">Categoria:</label>
                                        <input type="text" name="categoria" maxlength="40" size="40"
                                            style="border: none; font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"
                                            value="<?php echo htmlspecialchars($categoria); ?>"><br><br>

                                        <label for="imagen">Imágen:</label>
                                        <input type="text" name="imagen"
                                            style="border: none; font-size: 16px; height: 30px; border-radius: 5px; width: 100%;"
                                            value="<?php echo htmlspecialchars($imagenActual); ?>"><br><br>

                                        <label for="nuevaImagen">Nueva Imagen:</label>
                                        <input type="file" name="nuevaImagen" accept="image/*"><br><br>

                                        <div style="text-align: center;">
                                            <img src="<?php echo htmlspecialchars($imagenActual); ?>"
                                                alt="Vista previa de la imagen"
                                                style="width: 200px; height: 200px; margin: auto;">
                                        </div>

                                        <br><br>

                                        <input type='submit' name='confirmar'
                                            style="border: none; background-color: black; color: white; font-size: 16px; height: 50px; border-radius: 5px; width: 100%;"
                                            value='Confirmar'><br><br>
                                    </form>
                                </div>
                            </div>

                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-md-12 mt-1 mb-5 text-center align-self-center">
                                        <div id="botonAtras">
                                            <a class="col-md-10 mt-5 mb-5 btn btn-secondary " style="color: black;"
                                                href="registroarticulos.php">Volver</a>
                                        </div>
                                        <button class="get-started-btn scrollto" style="color: black;"
                                            onclick="window.location.href='sesionadmin.php'">
                                            Ir a la página de usuario
                                        </button>
                                    </div>
                                </div>
                            </div>
</body>

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
                                condiciones</a>
                        </li>
                        <li><i class="bx bx-chevron-right"></i> <a href="politicadeprivacidad.html">Politica de
                                Privacidad</a>
                        </li>
                        <li><i class="bx bx-chevron-right"></i>
                            < a href="contacto.html">contacto</a>
                        </li>
                        <li><i class="bx bx-chevron-right"></i> <a href="login.php">Log
                                In</a></li>
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
            &copy; Copyright <strong><span>Lobos Riders M.G.</span></strong>. Todos los
            Derechos Reservados
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

<!-- Gestión de Formulario en JS -->
<script>
    function confirmarCambios() {
        // Obtener el formulario por su ID
        document.getElementById('submitBtn').click();
    }
</script>
</body>

</html>