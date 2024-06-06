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
                                        <p>Merchandising de Socio</p>

                                    </div>

                                </div>
                            </div>

                        </section>
                        </section>
                        <div class="row">
                            <?php
                            if (isset($_SESSION["usuario"])) {
                                $usuario = $_SESSION["usuario"]->getUsuario();
                                echo "<h3>Usuario: " . $usuario . "<h3>";
                            } else {
                                echo "Usuario no Registrado";
                            }
                            ?>
                            </span>
                        </div>
                        <div class="col-10">
                            <div class="panel panel-default">

                                <div class="panel-body">

                                    <h1>Merchandising Pedido</h1>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Talla</th>
                                                <th>Género</th>
                                                <th>Tipo de Prenda</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $conn = conectar_DB();

                                            if (isset($_GET['cod_articulo']) && isset($_GET['cantidad']) && isset($_GET['talla']) && isset($_GET['genero']) && isset($_GET['tipo_prenda'])) {
                                                $cod_articulo = $_GET['cod_articulo'];
                                                $cantidad = $_GET['cantidad'];
                                                $talla = $_GET['talla'];
                                                $genero = $_GET['genero'];
                                                $tipo_prenda = $_GET['tipo_prenda'];

                                            }

                                            if (isset($_SESSION['carrito'])) {

                                                if (isset($_GET['cod_articulo']) || isset($_GET['codigo_articulo'])) {

                                                    $articulo = Articulo::obtenerArticuloPorCodigo($cod_articulo);
                                                    $_SESSION['carrito'][] = $articulo;


                                                    if ($codigo_articulo) {
                                                        $articulo = Articulo::obtenerArticuloPorCodigo($codigo_articulo);

                                                        $nombre = $articulo->getNombre();
                                                        $precio = $articulo->getPrecio();
                                                        $cantidad = 1;

                                                        if (isset($_SESSION["usuario"])) {
                                                            $usuario = $_SESSION["usuario"]->getUsuario();
                                                            $dni = Usuario::obtenerDniPorUsuario($usuario);
                                                            #echo $dni;
                                                            #echo $codigo_articulo;
                                                            // Verificar si se obtuvo el DNI
                                                            if ($dni) {
                                                                $conn = conectar_DB();

                                                                // Insertar los artículos en la tabla articulos_pedido
                                                                $fecha_creacion = date('Y-m-d H:i:s');
                                                                $codigo_articulo;
                                                                $cod_articulospedido = $fecha_creacion . "-" . $codigo_articulo;
                                                                $nombre = $articulo->getNombre();
                                                                $precio = $articulo->getPrecio();
                                                                $talla = isset($_GET['talla']) ? $_GET['talla'] : '';
                                                                $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
                                                                $tipo_prenda = isset($_GET['tipo_prenda']) ? $_GET['tipo_prenda'] : '';
                                                                $dni;


                                                                // Preparar la consulta SQL
                                                                $sql = "INSERT INTO articulos_pedido (cod_articulospedido, cod_articulo, nombre, precio, talla, genero, tipo_prenda, cod_pedido, cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                $stmt = $conn->prepare($sql);

                                                                // Enlazar parámetros
                                                                $stmt->bindParam(1, $cod_articulospedido, PDO::PARAM_STR);
                                                                $stmt->bindParam(2, $cod_articulo, PDO::PARAM_STR);
                                                                $stmt->bindParam(3, $nombre, PDO::PARAM_STR);
                                                                $stmt->bindParam(4, $precio, PDO::PARAM_STR);
                                                                $stmt->bindParam(5, $talla, PDO::PARAM_STR);
                                                                $stmt->bindParam(6, $genero, PDO::PARAM_STR);
                                                                $stmt->bindParam(7, $tipo_prenda, PDO::PARAM_STR);
                                                                $stmt->bindParam(8, $cod_pedido, PDO::PARAM_STR);
                                                                $stmt->bindParam(9, $dni, PDO::PARAM_STR);



                                                                // Ejecutar la consulta
                                                                $stmt->execute();

                                                                // Cerrar la conexión
                                                                $conn = null;

                                                                // Actualizar el total de artículos en el carrito
                                                                $total_articulos = count($_SESSION['carrito']);
                                                            } else {
                                                                // Error al obtener el DNI del usuario
                                                                echo "Error: No se pudo obtener el DNI del usuario.";
                                                            }
                                                        } else {
                                                            // El usuario no está autenticado
                                                            echo "Error: El usuario no está autenticado.";
                                                        }
                                                    }

                                                }
                                                foreach ($_SESSION['carrito'] as $articulo) {
                                                    // Mostrar cada artículo en la tabla
                                                    $cod_articulospedido = $articulo->getCod_articulo();
                                                    $nombre = $articulo->getNombre();
                                                    $precio = $articulo->getPrecio();
                                                    $talla = isset($_GET['talla']) ? $_GET['talla'] : '';
                                                    $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
                                                    $tipo_prenda = isset($_GET['tipo_prenda']) ? $_GET['tipo_prenda'] : '';
                                                    $cantidad = 1;
                                                    $subtotal = $precio * $cantidad;
                                                    $precio_total += $subtotal;
                                                    echo '<tr>
                                                        <td>' . $nombre . '</td>
                                                        <td>' . $precio . ' €</td>
                                                        <td>' . $talla . '</td>
                                                        <td class="text-center"> ' . $genero . '</td>
                                                        <td class="text-center"> ' . $tipo_prenda . '</td>
                                                        <td class="text-center">';
                                                    if (empty($datos_pedido)) {
                                                        echo '<a href="accioncarrito.php?action=removeCartItem&nombre=' . $nombre . '&cod_articulospedido=' . $cod_articulospedido . '" class="btn btn-danger" onclick="return confirm(\'¿Esta seguro de eliminar el articulo?\')">Eliminar<i class="glyphicon glyphicon-trash"></i></a>';
                                                    } else {
                                                        echo 'En Pedido';
                                                    }
                                                    echo '</td>
                                                    </tr>';

                                                }

                                            }


                                            ?>
                                            <script>
                                                // Eliminar el parámetro cod_articulo de la URL para que no se añada al recargar la pagina
                                                history.pushState({}, document.title, window.location.pathname);
                                            </script>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4">
                                                    <h3>Total de Artículos: <?php
                                                    $total_articulos = count($_SESSION['carrito']);
                                                    echo $total_articulos;
                                                    echo "</h3></td> <td>";
                                                    echo "<h3>Precio Total:";
                                                    $precio_total = isset($precio_total) ? $precio_total : 0;
                                                    echo $precio_total;
                                                    ?></h3>
                                                </td>
                                                <?php
                                                $conn = conectar_DB();
                                                //_____print_r($_SESSION["carrito"]);
                                                //Buscar si existe pedido realizado
                                                if (isset($_SESSION["usuario"])) {

                                                    $dni = $_SESSION["usuario"]->getDni();

                                                    $sql2 = "SELECT * FROM articulos_pedido WHERE cliente = :cliente";

                                                    // Preparar la declaración
                                                    $stmt = $conn->prepare($sql2);

                                                    // Enlazar parámetros
                                                    $stmt->bindParam(':cliente', $dni);

                                                    // Ejecutar la consulta
                                                    $stmt->execute();

                                                    $datos_pedido = array();

                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $datos_pedido[] = array(
                                                            'cod_articulospedido' => $row['cod_articulospedido'],
                                                            'cod_articulo' => $row['cod_articulo'],
                                                            'nombre' => $row['nombre'],
                                                            'precio' => $row['precio'],
                                                            'talla' => $row['talla'],
                                                            'genero' => $row['genero'],
                                                            'tipo_prenda' => $row['tipo_prenda'],
                                                            'cod_pedido' => $row['cod_pedido'],
                                                            'cliente' => $row['cliente'],
                                                        );
                                                    }

                                                    // Cerrar conexión
                                                    $conn = null;

                                                }
                                                if (!empty($cod_pedido)) {
                                                    //Obtener cod_pedido del array
                                                    foreach ($cod_pedido as $pedido) {
                                                        // Accede al valor de 'cod_pedido' y guárdalo en una variable
                                                        $cod_pedido = $pedido['cod_pedido'];
                                                        // Muestra el valor de 'cod_pedido'
                                                        echo "<h3>El siguiente pedido con codigo $cod_pedido: En Proceso. </h3>";
                                                    }
                                                } else {
                                                    echo '<td class="text-center"><a href="solicitudsocio.php" class="btn btn-dark btn-block">Gestionar Pedido <i class="glyphicon glyphicon-menu-right"></i></a></td>';
                                                }
                                                ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="panel-footer">
                                    <?php if (!empty($datos_pedido)) {
                                        echo '<a href="peticionmerchandisingsocio.php" class="btn btn-secondary"><i class="glyphicon glyphicon-menu-left"></i> Volver a la Tienda</a>';
                                    } else {
                                        echo '<a href="peticionmerchandisingsocio.php" class="btn btn-secondary"><i class="glyphicon glyphicon-menu-left"></i> Seguir Comprando</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div><!--Panek cierra-->
                    </div>

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

        function removeCartItem(nombre) {
            $.get("accioncarrito.php", { action: "removeCartItem", nombre }, function (data) {
                if (data == 'ok') {
                    location.reload();
                } else {
                    alert('No se ha podido actualizar. Inténtelo de nuevo.');
                }
            });
        }
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