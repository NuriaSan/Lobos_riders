<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");

// Comprobar si está autentificado
if (!isset($_SESSION["usuario"]) || !$_SESSION["autentificado"]) {
    header('Location: Pagina-Index.php');
    exit();
}

// Obtenemos el usuario de la sesión
if (isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500,600,600i,700,700i"
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
            <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt="" class="img-fluid"></a>
            <h1 class="logo me-auto me-lg-0"><a href="index.html"><span>L</span>obos <span>R</span>iders M.G<span>.</span></a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
            </nav>
            <a href="logout.php" class="get-started-btn scrollto">Log Out</a>
        </div>
    </header>
    <!-- End Header -->

    <!-- Main -->
    <main id="main">
        <div id="contenedor" class="container mt-5">
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="row justify-content-center">
                <div class="col-md-8 mb-5">
                    <div class="card">
                        <div class="card-header bg-black text-white text-center">
                            <a class="logo-custom"><img src="assets/img/logo-lobos.jpg" alt="" style="max-width: 280px;"></a>
                            <h1>Bienvenido a tu Espacio</h1>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">Estos son los datos que puedes modificar:</h2>
                            <?php
                            $conn = conectar_DB();

                            if (!$conn) {
                                die("Error al conectar a la base de datos");
                            }

                            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cod_pedido'])) {
                                $cod_pedido = $_GET['cod_pedido'];

                                try {
                                    // Obtener los datos actuales del artículo
                                    $stmt = $conn->prepare("SELECT * FROM articulos WHERE cod_pedido = :cod_pedido");
                                    $stmt->bindParam(':cod_pedido', $cod_pedido);
                                    $stmt->execute();
                                    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($pedido) {
                                        ?>
                                        <form id="formulariomodificar" method="POST" action="">
                                            <input type="hidden" name="cod_pedido" value="<?php echo htmlspecialchars($pedido['cod_pedido']); ?>">
                                            <input type="hidden" name="cod_usuario" value="<?php echo htmlspecialchars($pedido['cod_usuario']); ?>">
                                            <div class="mb-3">
                                                <label for="estatus" class="form-label">Estatus:</label>
                                                <input type="text" id="estatus" name="estatus" class="form-control"
                                                       value="<?php echo htmlspecialchars($pedido['estatus']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="pago" class="form-label">Pago:</label>
                                                <input type="text" id="pago" name="pago" class="form-control"
                                                       value="<?php echo htmlspecialchars($pedido['pago']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_pedido" class="form-label">Fecha Pedido:</label>
                                                <input type="text" id="fecha_pedido" name="fecha_pedido" class="form-control"
                                                       value="<?php echo htmlspecialchars($pedido['fecha_pedido']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_envio" class="form-label">Fecha Envío:</label>
                                                <input type="text" id="fecha_envio" name="fecha_envio" class="form-control"
                                                       value="<?php echo htmlspecialchars($pedido['fecha_envio']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="total" class="form-label">Total:</label>
                                                <input type="text" id="total" name="total" class="form-control"
                                                       value="<?php echo htmlspecialchars($pedido['total']); ?>">
                                            </div>
                                            <button type="submit" id="submitBtn" name="confirmar" value="Confirmar"
                                                    style="display: none;"></button>
                                        </form>
                                        <?php
                                    } else {
                                        echo "Artículo no encontrado.";
                                    }
                                } catch (PDOException $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                            }

                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                // Obtener los valores del formulario
                                $cod_pedido = $_POST['cod_pedido'];
                                $cod_usuario = $_POST['cod_usuario'];
                                $estatus = $_POST['estatus'];
                                $pago = $_POST['pago'];
                                $fecha_pedido = $_POST['fecha_pedido'];
                                $fecha_envio = $_POST['fecha_envio'];
                                $total = $_POST['total'];

                                try {
                                    // Actualizar los datos del artículo
                                    $stmt = $conn->prepare("UPDATE pedidos 
                                        SET cod_usuario = :cod_usuario, 
                                            estatus = :estatus, 
                                            pago = :pago, 
                                            fecha_pedido = :fecha_pedido, 
                                            fecha_envio = :fecha_envio, 
                                            total = :total
                                        WHERE cod_pedido = :cod_pedido");
                                    $stmt->bindParam(':cod_usuario', $cod_usuario);
                                    $stmt->bindParam(':estatus', $estatus);
                                    $stmt->bindParam(':pago', $pago);
                                    $stmt->bindParam(':fecha_pedido', $fecha_pedido);
                                    $stmt->bindParam(':fecha_envio', $fecha_envio);
                                    $stmt->bindParam(':total', $total);
                                    $stmt->bindParam(':cod_pedido', $cod_pedido);

                                    if ($stmt->execute()) {
                                        // Redirigir a registropedidos.php con mensaje de éxito
                                        header('Location: registropedidos.php?mensaje=exito');
                                        exit();
                                    } else {
                                        // Redirigir a registropedidos.php con mensaje de error
                                        header('Location: registropedidos.php?mensaje=error');
                                        exit();
                                    }
                                } catch (PDOException $e) {
                                    // Redirigir a registropedidos.php con mensaje de error
                                    $error = 'Error: ' . $e->getMessage();
                                    header('Location: registropedidos.php?mensaje=' . urlencode($error));
                                    exit();
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="contenedor" class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 mb-5">
                    <div class="card">
                        <div id="accionesusuario" class="text-center p-4">
                            <div class="titulo2">
                                <h2>¿Quiere confirmar el cambio de datos?</h2>
                                <p>Deberá tener en cuenta que tras modificar los campos y confirmar los cambios se harán permanentes.</p>
                            </div>
                            <div class="mt-4">
                                <button class="get-started-btn scrollto" style="color: black;" data-bs-toggle="modal"
                                        data-bs-target="#confirmarModal">
                                    Confirmar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main -->

    <!-- Bloque de Confirmación-->
    <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarModalLabel">Confirmar Cambios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas modificar los datos?
                </div>
                <div class="modal-footer">
                    <button type="button" class="get-started-btn scrollto" style="color: black;"
                            data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="get-started-btn scrollto" style="color: black;"
                            onclick="confirmarCambios()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 mt-5 mb-5 text-center align-self-center">
                <button class="get-started-btn scrollto" style="color: black;"
                        onclick="window.location.href='sesionadmin.php'">
                    Ir a la página de usuario
                </button>
            </div>
        </div>
    </div>

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
                            <li><i class="bx bx-chevron-right"></i> < a href="contacto.html">contacto</a></li>
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
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
