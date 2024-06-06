<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include_once ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");


include ("seguridad.php");

$mensajeExito = isset($_GET['mensaje']) && $_GET['mensaje'] === 'exito';
$mensajeEliminado = isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado';

// Comprobar si está autentificado
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

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container bg-black d-flex align-items-center justify-content-lg-between">

            <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt=""
                    class="img-fluid"></a>
            <h1 class="logo me-auto me-lg-0"><a href="index.html"><span>L</span>obos <span>R</span>iders
                    M.G<span>.</span></a>
            </h1>

            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>

        </div>
    </header>
    <!-- End Header -->

    <!-- Main -->
    <main id="main">
        <?php if ($mensajeExito): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>&#128588; Éxito:</strong> Se han modificado los datos de forma correcta
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($mensajeEliminado): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>&#128588; Éxito:</strong> Se ha eliminado el artículo correctamente
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div id="contenedor" class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt=""
                                    style="max-width: 280px;"></a>
                            <h1>Bienvenido a tu Espacio, <?php echo $nombre; ?></h1>
                            <h4>Tienes privilegios de Administrador</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">Añadir Articulo de Merchandising:</h2>
                            <?php if ($mensajeExito): ?>
                                <div class="alert alert-success" role="alert">&#128588; Se han modificado los datos de forma
                                    correcta &#128588;</div>
                            <?php endif; ?>
                            <div id="contenedor" class="container mt-12">
                                <div class="row">
                                    <div class="container mt-5">
                                        <div id="contenedor">
                                            <form name="formarticulos" method="post" action="formularioarticulos.php"
                                                enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="codigo" class="form-label">Código del Artículo:</label>
                                                    <input name="codigo" type="text" id="codigo" maxlength="8" size="8"
                                                        class="form-control" placeholder="Autogenerado" disabled>
                                                </div>

                                                <div class="mb-3">
                                                    <input name="nombre" type="text" id="tipo" maxlength="40" size="40"
                                                        class="form-control" placeholder="Nombre" required>
                                                </div>

                                                <div class="mb-3">
                                                    <input name="precio" type="number" id="precio" maxlength="15"
                                                        size="15" step="0.01" min="0" max="999999999999.99"
                                                        class="form-control" placeholder="Precio €" required>
                                                </div>

                                                <div class="mb-3">
                                                    <textarea name="descripcion" id="descripcion" class="form-control"
                                                        placeholder="Descripción" required></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <input name="categoria" type="text" id="categoria" maxlength="40"
                                                        size="40" class="form-control" placeholder="Categoría" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="imagen" class="form-label">Imagen:</label>
                                                    <input name="imagen" id="imagen" type="file"
                                                        accept=".jpeg, .jpg, .png, .gif" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <input type="submit" name="enviar" value="Enviar"
                                                        class="btn btn-primary">
                                                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"
                                                        class="btn btn-secondary">Volver Atrás</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php

                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {

                                        $nombre = $_POST['nombre'];
                                        $precio = $_POST['precio'];
                                        $descripcion = $_POST['descripcion'];
                                        $categoria = $_POST['categoria'];
                                        $nombre_archivo = $_FILES['imagen']['name'];
                                        $imagen = "articulosImg/" . $nombre_archivo;
                                        $temp = $_FILES['imagen']['tmp_name'];
                                        $size = $_FILES['imagen']['size'];


                                        //Validación que comprueba si se insertan números positivos
                                        if (!is_numeric($precio) || $precio <= 0) {
                                            echo '<div style="color: red; text-align: center; font-size: 20px; margin-top: 50px;">elprecio debe ser un numero positivo.</div>';
                                        }

                                        //Validación de la exixtencia y tamaño para controlar que no sea excesivo
                                        if (isset($_FILES['imagen']) && ($_FILES['imagen']['size'] > 0)) {

                                            if ($size > 5000000) {
                                                echo '<div style="color: red; text-align: center; font-size: 20px; margin-top: 50px;">El archivo introducido es demasiado pesado.</div>';
                                            }

                                        } else {
                                            //______________________Mejorar respuesta
                                            echo "No se ha podido cargar el archivo.";
                                        }

                                        // Validación del tipo de archivo
                                        $extensionesTipos = ['jpg', 'jpeg', 'png', 'gif'];
                                        $archivoExtension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));


                                        if (!in_array($archivoExtension, $extensionesTipos) || !exif_imagetype($_FILES['imagen']['tmp_name'])) {
                                            echo '<div><b>Por favor, seleccione un archivo de imagen válido.</b></div>';
                                            exit();
                                        }


                                        if (move_uploaded_file($temp, $imagen)) {

                                            $conn = conectar_DB();

                                            if ($conn) {

                                                // Crea una instancia de la clase Articulo
                                                $articulo = new Articulo('aaa00001', $nombre, $precio, $descripcion, $categoria, $imagen);

                                                // Obtiene el código utilizando el método de la clase
                                                $codigoArticulo = $articulo->generarCodigo();

                                                // Actualiza el código del artículo
                                                $articulo->setCod_articulo($codigoArticulo);

                                                $stmt = $conn->prepare("INSERT INTO merchandising (cod_articulo, nombre, precio, descripcion, categoria, imagen) VALUES (:cod_articulo, :nombre, :precio, :descripcion, :categoria, :imagen)");
                                                $stmt->bindParam(':cod_articulo', $codigoArticulo);
                                                $stmt->bindParam(':nombre', $nombre);
                                                $stmt->bindParam(':precio', $precio);
                                                $stmt->bindParam(':descripcion', $descripcion);
                                                $stmt->bindParam(':categoria', $categoria);
                                                $stmt->bindParam(':imagen', $imagen);

                                                if ($stmt->execute()) {

                                                    echo '<div class="alert alert-success" role="alert" style="margin-top: 20px;">Datos insertados correctamente. Redirigiendo al registro.</div>';

                                                    echo '<meta http-equiv="refresh" content="3;url=registroarticulos.php">';
                                                } else {
                                                    echo '<div><b>Ocurrió algún error al insertar en la base de datos.</b></div>';
                                                }
                                            } else {
                                                echo '<div style="color: red; text-align: center; font-size: 20px; margin-top: 50px;">Error en la conexión de la Base de Datos.</div>';
                                            }

                                        } else {
                                            //Respuesta en el caso de que no se haya podido subir la imagen, se muestra un mensaje de error
                                            echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
                                        }
                                    }
                                    ?>

</body>
</main>
<!-- End Main -->



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