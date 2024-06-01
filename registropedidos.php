<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");


include ("seguridad.php");

// Comprobar si está autentificado
if (!isset($_SESSION["usuario"]) || !$_SESSION["autentificado"]) {
    //echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "Pagina-Index.php"; }, 2000);</script>';
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
$mensajeExito = isset($_GET['mensaje']) && $_GET['mensaje'] === 'exito';
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
        <div id="contenedor" class="container mt-12">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php if (isset($_GET['mensaje'])): ?>
                        <?php if ($_GET['mensaje'] === 'exito'): ?>
                            <div class="alert alert-success" role="alert">
                                Datos actualizados correctamente.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET['mensaje']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
                <div class="titulo">
                    <h1 style="color: orange;">Bienvenido a tu Espacio de Administrador <?php echo $nombre; ?></h1>
                </div>

                <div id="formularioad" class="container mt-4">
                    <h3>Tabla de Articulos Solicitados</h3>
                    <form action="registropedidos.php" method="get">
                        <div class="table-responsive">
                            <table class="table table-hover table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo Articulos Pedido</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Género</th>
                                        <th scope="col">Tipo de Prenda</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col">Socio</th>
                                        <th scope="col">Modificar</th>
                                        <th scope="col">Borrar</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    //Controla si se ha iniciado la sesión anteriormente
                                    if (session_status() == PHP_SESSION_NONE) {
                                        session_start();
                                    }

                                    $conn = conectar_DB();

                                    if (!$conn) {
                                        die("Error al conectar a la base de datos");
                                    }
                                    try {
                                        // Consulta para obtener los clientes
                                    
                                        $stmt = $conn->prepare("SELECT ap.*, u.admin, u.socio
                                        FROM articulos_pedido ap
                                        JOIN usuarios u ON ap.cliente = u.dni
                                        ");
                                        $stmt->execute();

                                        while ($datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . $datos["cod_articulospedido"] . "</td>";
                                            echo "<td>" . $datos["nombre"] . "</td>";
                                            echo "<td>" . $datos["precio"] . "</td>";
                                            echo "<td>" . $datos["talla"] . "</td>";
                                            echo "<td>" . $datos["genero"] . "</td>";
                                            echo "<td>" . $datos["tipo_prenda"] . "</td>";
                                            echo "<td>" . $datos["cliente"] . "</td>";
                                            // Obtener si o no como respuesta de los campos admin y socio
                                            $adminText = ($datos["admin"] == 1) ? 'Sí' : 'No';
                                            $socioText = ($datos["socio"] == 1) ? 'Sí' : 'No';
                                            echo "<td>" . $adminText . "</td>";
                                            echo "<td>" . $socioText . "</td>";
                                            echo "<td><a href='articulospedidomodificar.php?cod_articulospedido=" . $datos["cod_articulospedido"] . "'>&#128221;</a></td>";
                                            echo "<td><a href='articulospedidoeliminar.php?cod_articulospedido=" . $datos["cod_articulospedido"] . "'>&#10060;</a></td>";
                                            echo "</tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo 'Error: ' . $e->getMessage();
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <h3>Tabla de Pedidos Realizados</h3>
                    <form action="registropedidos.php" method="get">
                        <div class="table-responsive">
                            <table class="table table-hover table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo de Pedido</th>
                                        <th scope="col">Codigo de Usuario</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Pago</th>
                                        <th scope="col">Fecha de Pedido</th>
                                        <th scope="col">Fecha de entrega</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col">Socio</th>
                                        <th scope="col">Modificar</th>
                                        <th scope="col">Borrar</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    //Controla si se ha iniciado la sesión anteriormente
                                    if (session_status() == PHP_SESSION_NONE) {
                                        session_start();
                                    }

                                    $conn = conectar_DB();

                                    if (!$conn) {
                                        die("Error al conectar a la base de datos");
                                    }
                                    try {
                                        // Consulta para obtener los clientes
                                    
                                        $stmt = $conn->prepare("SELECT ap.*, u.admin, u.socio
                                        FROM pedidos ap
                                        JOIN usuarios u ON ap.cod_usuario = u.dni
                                        ");
                                        $stmt->execute();

                                        while ($datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . $datos["cod_pedido"] . "</td>";
                                            echo "<td>" . $datos["cod_usuario"] . "</td>";
                                            echo "<td>" . $datos["estatus"] . "</td>";
                                            echo "<td>" . $datos["pago"] . "</td>";
                                            echo "<td>" . $datos["fecha_pedido"] . "</td>";
                                            echo "<td>" . $datos["fecha_envio"] . "</td>";
                                            echo "<td>" . $datos["total"] . "</td>";
                                            // Obtener si o no como respuesta de los campos admin y socio
                                            $adminText = ($datos["admin"] == 1) ? 'Sí' : 'No';
                                            $socioText = ($datos["socio"] == 1) ? 'Sí' : 'No';
                                            echo "<td>" . $adminText . "</td>";
                                            echo "<td>" . $socioText . "</td>";
                                            echo "<td><a href='pedidomodificar.php?cod_pedido=" . $datos["cod_pedido"] . "'>&#128221;</a></td>";
                                            echo "<td><a href='pedidoeliminar.php?cod_pedido=" . $datos["cod_pedido"] . "'>&#10060;</a></td>";
                                            echo "</tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo 'Error: ' . $e->getMessage();
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                </form>
            </div>
            <br>
            <br>
            <div class="col-md-12">
                <div id="accionesadmin">

                    <div id="verdatos" class="mb-3">
                        <button class="btn btn-secondary w-100" type="button" onclick="verUsuario()">Volver</button>
                    </div>
                    <div id="salir" class="mb-3">
                        <button class="btn btn-dark w-100" type="button" onclick="logout()">Salir de la Sesión</button>
                    </div>
                </div>
            </div>

            <script>
                //Uso de funciones para redirigir a diferentes formularios con botones
                function verUsuario() {
                    window.location.href = "sesionadmin.php?dni=";
                }
                function logout() {
                    window.location.href = "logout.php";
                }
            </script>
        </div>
        </div>
        </div>
        </div>
    </main>
    <!-- End Main -->
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