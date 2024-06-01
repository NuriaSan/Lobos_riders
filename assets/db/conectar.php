<?php
    if (!function_exists('conectar_DB')) {
        function conectar_DB(){
            $host = "localhost";
            $nombrebd = "lobos_riders";
            $username = "root";
            $password = "";

            try {
                $conn = new PDO("mysql:host=$host;dbname=$nombrebd;charset=utf8", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
            echo "<br><br>";
            return $conn;
        }
    }  
?>
