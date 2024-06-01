<?php
include_once("assets/db/conectar.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cod_articulospedido'])) {
        $cod_articulospedido = $_POST['cod_articulospedido'];

        try {
            // Conexión a la base de datos
            $conn = conectar_DB();

            // Eliminar el artículo
            $stmt = $conn->prepare("DELETE FROM articulos_pedido WHERE cod_articulospedido = :cod_articulospedido");
            $stmt->bindParam(':cod_articulospedido', $cod_articulospedido);

            if ($stmt->execute()) {
                header('Location: registropedidos.php?mensaje=exito');
                exit();
            } else {
                header('Location: registropedidos.php?mensaje=error');
                exit();
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
            header('Location: registropedidos.php?mensaje=' . urlencode($error));
            exit();
        }
    } else {
        header('Location: registropedidos.php?mensaje=error');
        exit();
    }
} else {
    header('Location: registropedidos.php');
    exit();
}
?>
