<?php
include_once ("assets/db/conectar.php");
include ("claseUsuario.php");
include_once ("claseArticulo.php");
session_start();
include_once ("funciones-comunes.php");
include ("seguridad.php");

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
                            $conn = conectar_DB();

                            if (!$conn) {
                                die("Error al conectar a la base de datos");
                            }

                            if (isset($_GET["cod_pedido"])) {
                                $cod_pedido = $_GET["cod_pedido"];

                                try {
                                    $stmt = $conn->prepare("SELECT * FROM pedidos WHERE cod_pedido = :cod_pedido");
                                    $stmt->bindParam(':cod_pedido', $cod_pedido);
                                    $stmt->execute();
                                    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($pedido) {
                                        ?>
                                        <h2>Confirmar Eliminación</h2>
                                        <p>El pedido con código <?php echo htmlspecialchars($pedido["cod_pedido"]); ?> será eliminado. Si confirma la acción no se podrán recupersr los datos eliminados. </p>
                                        <p>¿Estás seguro de que deseas eliminar el pedido?</p>
                                        <form action='confirmar_eliminacion_pedidos.php' method='post'>
                                            <input type='hidden' name='cod_pedido' value='<?php echo htmlspecialchars($pedido["cod_pedido"]); ?>'>
                                            <input type='submit' name='confirmar' value='Sí, Eliminar' class='btn btn-danger'>
                                            <button type='button' onclick='history.back()' class='btn btn-secondary'>No, Volver Atrás</button>
                                        </form>
                                        <?php
                                    } else {
                                        echo "Pedido no encontrado.";
                                    }
                                } catch (PDOException $e) {
                                    echo 'Error: ' . $e->getMessage();
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
        <!-- Footer content -->
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
