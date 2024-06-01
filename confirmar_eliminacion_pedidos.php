<?php
include_once("assets/db/conectar.php");
session_start();
include_once("seguridad.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cod_pedido'])) {
        $cod_pedido = $_POST['cod_pedido'];

        $conn = conectar_DB();

        if (!$conn) {
            die("Error al conectar a la base de datos");
        }

        try {
            $stmt = $conn->prepare("DELETE FROM pedidos WHERE cod_pedido = :cod_pedido");
            $stmt->bindParam(':cod_pedido', $cod_pedido);
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
    }
} else {
    header('Location: registropedidos.php');
    exit();
}
?>
