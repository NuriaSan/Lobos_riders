<?php
include_once("claseArticulo.php");
include_once ("assets/db/conectar.php");

session_start();

//var_dump($_GET['nombre']);
//var_dump($_SESSION['carrito']);
$nombre = $_GET['nombre'];


// Obtiene el artículo por su nombre desde la base de datos
$articulos = Articulo::obtenerArticulosPorNombre($nombre);


// Verificar si $_SESSION["carrito"] está definido
if(isset($_SESSION["carrito"])) {
    // Recorrer el carrito y buscar el producto por su nombre
    foreach ($_SESSION["carrito"] as $key => $articulo) {
        if($articulo->getNombre() === $nombre) {
            // Eliminar el producto del carrito
            unset($_SESSION["carrito"][$key]);

            break; // Romper el bucle una vez que se ha eliminado el producto
        }
    }
}
    if ($nombre){
        $conn = conectar_DB(); 
        $sql = "DELETE FROM articulos_pedido WHERE nombre = ?";

                    // Preparar la declaración
                    $stmt = $conn->prepare($sql);

                    // Enlazar parámetros
                    $stmt->bindParam(1, $nombre, PDO::PARAM_STR);

                    // Ejecutar la consulta
                    $stmt->execute();
                    $filasAfectadas = $stmt->rowCount();
                    
                    $conn = null;             
    }


// Redirigir de vuelta a la página del carrito después de la eliminación

echo '<meta http-equiv="refresh" content="0;url=vercarta.php">';
exit();
?>
