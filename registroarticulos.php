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
        <div id="contenedor" class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt=""
                                    style="max-width: 280px;"></a>
                            <h1>Bienvenido a tu Espacio, <?php echo $nombre; ?></h1>
                            <h4>Tienes privilegios de Administrador</h4>
                        </div>
                        <?php if ($mensajeExito): ?>
                            <div class="alert alert-success alert-dismissible fade show m-5" role="alert">
                                <strong>&#128588; Éxito:</strong> Se han modificado los datos de forma correcta
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($mensajeEliminado): ?>
                            <div class="alert alert-success alert-dismissible fade show m-5" role="alert">
                                <strong>&#128588; Éxito:</strong> Se ha eliminado el artículo correctamente
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title text-center">Estas son las acciones disponibles:</h2>
                            <div id="contenedor" class="container mt-12">
                                <div class="row">
                                    <div class="container mt-5 text-center">
                                        <form action="registroarticulosadministrador.php" method="get">
                                            <div class="acciones d-flex justify-content-between mt-5">
                                                <div id="botonAgregar">
                                                    <a class="get-started-btn scrollto" style="color: black;"
                                                        href="formularioarticulos.php">Agregar Articulo</a>
                                                </div>
                                                <div id="botonAtras">
                                                    <a class="btn btn-secondary" style="color: black;"
                                                        href="sesionadmin.php">Volver a la Sesión Principal</a>
                                                </div>
                                                <div id="logout">
                                                    <a class="btn btn-dark" href="logout.php">Salir de la sesión</a>
                                                </div>
                                            </div>

                                            <h2 class="mt-4">Articulos Disponibles</h2>

                                            <div class="ordenacion_nombre mt-4">
                                                <p>Ordenar:</p>
                                                <!-- Ordenación con enlaces -->
                                                <a href="registroarticulos.php?sort=nombre_asc"
                                                    class="get-started-btn scrollto" style="color: black;">Nombre
                                                    Ascendente ▲</a>
                                                <a href="registroarticulos.php?sort=nombre_desc"
                                                    class="get-started-btn scrollto" style="color: black;">Nombre
                                                    Descendente ▼</a>
                                            </div>

                                            <input type='button' value='Volver' onclick='history.back()'
                                                class="btn btn-secondary mt-4">

                                            <table class="table table-striped mt-4">
                                                <tr>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Codigo</th>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Nombre</th>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Descripción</th>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Categoria</th>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Precio</th>
                                                    <th
                                                        style="background-color:white; font-size: 16px; padding:15px; margin:15px; border-radius: 5px">
                                                        Imagen</th>
                                                    <th
                                                        style="background-color: orange; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Modificar</th>
                                                    <th
                                                        style="background-color: orange; font-size: 16px; padding:15px; margin:15px; border-radius: 5px;">
                                                        Borrar</th>
                                                </tr>

                                                <?php
                                                //Controla si se ha iniciado la sesión anteriormente
                                                if (session_status() == PHP_SESSION_NONE) {
                                                    session_start();
                                                }


                                                $conn = conectar_DB();

                                                if (!$conn) {
                                                    die("Error al conectar a la base de datos");
                                                }

                                                $articulosPorPagina = 3;
                                                $pagina = 1;
                                                $inicio = 0;

                                                if (isset($_GET["pagina"])) {
                                                    $pagina = $_GET["pagina"];
                                                    $inicio = ($pagina - 1) * $articulosPorPagina;
                                                }

                                                try {
                                                    // Paginación
                                                    $stmt = $conn->prepare("SELECT * FROM merchandising");
                                                    $stmt->execute();
                                                    $totalArticulos = $stmt->rowCount();
                                                    $totalPaginas = ceil($totalArticulos / $articulosPorPagina);
                                                    $stmt = $conn->prepare("SELECT * FROM merchandising LIMIT :inicio, :articulosPorPagina");

                                                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'nombre_asc';
                                                    $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

                                                    switch ($sort) {
                                                        case 'nombre_asc':
                                                            $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda ORDER BY nombre ASC LIMIT :inicio, :articulosPorPagina";
                                                            break;
                                                        case 'nombre_desc':
                                                            $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda ORDER BY nombre DESC LIMIT :inicio, :articulosPorPagina";
                                                            break;
                                                        default:
                                                            $consulta = "SELECT * FROM merchandising WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda LIMIT :inicio, :articulosPorPagina";
                                                    }

                                                    $stmt = $conn->prepare($consulta);
                                                    $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
                                                    $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
                                                    $stmt->bindParam(':articulosPorPagina', $articulosPorPagina, PDO::PARAM_INT);
                                                    $stmt->execute();


                                                    while ($datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<tr>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>" . $datos["cod_articulo"] . "</td>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>" . $datos["nombre"] . "</td>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>" . $datos["descripcion"] . "</td>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>" . $datos["categoria"] . "</td>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>" . $datos["precio"] . '€' . "</td>
                                                            <td style='background-color:white; font-size: 16px; padding:15px; margin:15px;'>
                                                                <img src=' " . $datos["imagen"] . "' alt='imagen' style='width: 250px; height: 200px; margin:0; padding: 0;'>
                                                            <td style='background-color:white; font-size: 24px; padding: 20px;'><a href='articulosmodificar.php?cod_articulo=" . $datos["cod_articulo"] . "'>&#128221;</a></td>
                                                            <td style='background-color:white; font-size: 24px; padding: 20px;'><a href='articuloseliminar.php?cod_articulo=" . $datos["cod_articulo"] . "'>&#10060;</a></td>
                                                            </td>
                                                        </tr>";
                                                    }

                                                    echo "</div>";
                                                } catch (PDOException $e) {
                                                    echo 'Error: ' . $e->getMessage();
                                                }
                                                ?>
                                            </table>
                                            <div class="paginacion mt-4">
                                                <ul class="pagination">
                                                    <?php
                                                    for ($i = 1; $i <= $totalPaginas; $i++) {
                                                        $claseActiva = ($i == $pagina) ? 'active' : '';
                                                        echo "<li class='page-item $claseActiva' style='margin: 0 5px;'><a class='page-link' style='display: block; padding: 10px 15px; text-decoration: none; background-color: orange; color: black; border-radius: 5px; transition: background-color 0.3s ease;' href='registroarticulos.php?pagina=" . $i . "'>$i</a></li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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