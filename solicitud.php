<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");

// Comprobación de sesión activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500,600,600,700,700i"
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
                    <li><a href="eliminarusuario.php">Eliminar Datos</a></li>
                    <li><a href="modificarusuario.php">Modificar Usuario</a></li>
                    <li><a href="peticionmerchandising.php">Solicitar Merchandising</a></li>
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
                        <div class="panel-body">
                            <h1>Vista previa de la Orden</h1>
                            <?php if (isset($_SESSION["usuario"])) {
                                $usuario = $_SESSION["usuario"]->getUsuario();
                            } else {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Debes iniciar sesión para completar la compra.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }?>
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th class="text-center">Cantidad</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $precio_total = 0;
                                $total_articulos = count($_SESSION['carrito']);
                                foreach ($_SESSION['carrito'] as $articulo) {
                                    // Mostrar cada artículo en la tabla
                                    $codigo_articulo = $articulo->getCod_articulo();
                                    $nombre = $articulo->getNombre();
                                    $precio = $articulo->getPrecio();
                                    $cantidad = 1;
                                    $subtotal = $precio * $cantidad;
                                    $precio_total += $subtotal;
                                    echo '<tr>
                                        <td>'.$nombre.'</td>
                                        <td>'.$precio.' €</td>
                                        <td class="text-center"> '.$cantidad.'</td>
                                        
                                    </tr>';
                                }
                                ?>
                                    <tr>
                                        <td colspan="2"><h3>Total de Artículos: <?php echo $total_articulos; ?></h3></td>
                                        <td><h2>Total <?php echo $precio_total.' €'; ?></h2></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                            <div class="shipAddr">
                                
                                <?php
                                    if (isset($_SESSION["usuario"])) {
                                        $usuario = $_SESSION["usuario"]->getUsuario();
                                        //Sesion usuario
                                        // Utilizar la clase Usuario para obtener el DNI del usuario
                                            $dni = Usuario::obtenerDniPorUsuario($usuario);
                                        
                                            // Verificar si se obtuvo el DNI
                                            if ($dni) {
                                                // Utilizar la clase Usuario para obtener el objeto Usuario correspondiente al DNI
                                                $usuarioSesion = Usuario::obtenerUsuarioPorDNI($dni);
                                        
                                                // Verificar si se obtuvo un objeto Usuario
                                                if ($usuarioSesion) {
                                                    // Obtener los valores del objeto Usuario
                                                    $dni = $usuarioSesion->getDni();
                                                    $usuario = $usuarioSesion->getUsuario();
                                                    $nombre = $usuarioSesion->getNombre();
                                                    $telefono = $usuarioSesion->getTelefono();
                                                    $email = $usuarioSesion->getEmail();
                                                    $password = $usuarioSesion->getPassword();
                                                    echo '<h4>Datos del usuario '.$usuario.':</h4>';
                                                    echo '<table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nombre</th>
                                                                    <th>Telefono</th>
                                                                    <th>Email</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>';
                                                            echo '<tr>';
                                                            echo '<td>'. $nombre.'</td>';
                                                            echo '<td>'. $telefono.'</td>';
                                                            echo '<td>'. $email.'</td>';
                                                            echo '</tr>';
                                                    echo '</table>';

                                                        $cod_pedido ="COD-".date('YmdHis');
                                                        $cod_usuario = $dni;
                                                        $estatus = "en proceso";
                                                        $pago = "Pendiente";
                                                        $fecha_pedido = date("Y-m-d");
                                                        $fecha_envio = "Pendiente";
                                                        $total = $precio_total;
                                                        
                                                        // Insertar en la tabla sólo si no existe un pedido en proceso para el usuario
                                                        $conn = conectar_DB();
                                                        $stmt = $conn->prepare("SELECT * FROM pedidos WHERE cod_usuario = ? AND estatus = 'en proceso'");
                                                        $stmt->bindParam(1, $cod_usuario, PDO::PARAM_STR);
                                                        $stmt->execute();
                                                        
                                                        if ($stmt->rowCount() == 0) {
                                                            $sql = "INSERT INTO pedidos (cod_pedido, cod_usuario, estatus, pago, fecha_pedido, fecha_envio, total) VALUES (?, ?, ?, ?, ?, ?, ?)";

                                                            // Preparar la declaración
                                                            $stmt = $conn->prepare($sql);

                                                            // Enlazar parámetros
                                                            $stmt->bindParam(1, $cod_pedido, PDO::PARAM_STR);
                                                            $stmt->bindParam(2, $cod_usuario, PDO::PARAM_STR);
                                                            $stmt->bindParam(3, $estatus, PDO::PARAM_STR);
                                                            $stmt->bindParam(4, $pago, PDO::PARAM_STR);
                                                            $stmt->bindParam(5, $fecha_pedido, PDO::PARAM_STR);
                                                            $stmt->bindParam(6, $fecha_envio, PDO::PARAM_STR); 
                                                            $stmt->bindParam(7, $total, PDO::PARAM_STR);

                                                            $stmt->execute();
                                                        } else {
                                                            // Si ya existe un pedido en proceso, mostrar los datos del pedido existente
                                                            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            echo '<div style="color: red; text-align: center; font-size: 20px; margin-top: 20px;">Tienes registrada una solicitud en proceso.</div>';
                                                            echo '<h4>Detalles del pedido en proceso:</h4>';
                                                            echo '<table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Código Pedido</th>
                                                                            <th>Fecha Pedido</th>
                                                                            <th>Total</th>
                                                                            <th>Estatus</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>';
                                                                    echo '<tr>';
                                                                    echo '<td>'. $pedido['cod_pedido'].'</td>';
                                                                    echo '<td>'. $pedido['fecha_pedido'].'</td>';
                                                                    echo '<td>'. $pedido['total'].' €</td>';
                                                                    echo '<td>'. $pedido['estatus'].'</td>';
                                                                    echo '</tr>';
                                                            echo '</table>';
                                                        }
                                                        
                                                        $conn = null;
                                                        unset($_SESSION['carrito']);

                                                } else {
                                                    // Resultado en el caso en que no se encuentre el usuario
                                                    echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">No se ha podido obtener el usuario</div>';
                                                    exit();
                                                }
                                            } else {
                                                // Resultado si no se haya obtenido el DNI
                                                echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error: No se pudo obtener el DNI.</div>';
                                                exit();
                                            }
                                            
                                        } else {
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        No puedes obtener los datos del envio ni procesar el pedido.
                                        Para realizar todas estas acciones deberás registrarte.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                        echo '
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Debes iniciar sesión para completar el pedido.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            <a href="login.php" class="btn btn-secondary">Iniciar Sesión</a>
                                        </div>';
                                }?>
                                
                            </div>
                            <div class="footBtn">
        <?php
        // Verificar si existe un pedido en proceso para el usuario
        if (isset($_SESSION["usuario"])) {
            $usuario = $_SESSION["usuario"]->getUsuario();
            $dni = Usuario::obtenerDniPorUsuario($usuario);
            if ($dni) {
                $conn = conectar_DB();
                $stmt = $conn->prepare("SELECT * FROM pedidos WHERE cod_usuario = ? AND estatus = 'en proceso'");
                $stmt->bindParam(1, $dni, PDO::PARAM_STR);
                $stmt->execute();
                
                // Si existe un pedido en proceso, mostrar el botón para ir a sesionusuario.php
                if ($stmt->rowCount() > 0) {
                    echo '<a href="ordenexito.php?action=placeOrder&cod_pedido=<?php echo $cod_pedido; ?>" class="btn btn-secondary m-4">Visualizar la Solicitud<i class="glyphicon glyphicon-menu-right"></i></a>';
                    echo '<a href="sesionusuario.php" class="btn btn-dark">Ir a la sesión de Usuario</a>';
                } else {
                    // Si no existe un pedido en proceso, mostrar los botones normales
                    echo '<a href="peticionmerchandising.php" class="btn btn-secondary"><i class="glyphicon glyphicon-menu-left"></i> Continuar Comprando</a>';
                }
            }
        }
        ?>
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
