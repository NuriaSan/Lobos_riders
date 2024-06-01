<?php

    class Articulo {
        private $cod_articulo;
        private $nombre;
        private $precio;
        private $descripcion;
        private $categoria;
        private $imagen;
    

        public function __construct($cod_articulo, $nombre, $precio, $descripcion, $categoria, $imagen) {
            $this->codigo = $cod_articulo;
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->descripcion = $descripcion;
            $this->categoria = $categoria;
            $this->imagen = $imagen;
        }

        // Getters
        public function getCod_articulo() {
            return $this->cod_articulo;
        }
        public function getNombre() {
            return $this->nombre;
        }
        public function getPrecio() {
            return $this->precio;
        }
        public function getDescripcion() {
            return $this->descripcion;
        }
        public function getCategoria() {
            return $this->categoria;
        }
        public function getImagen() {
            return $this->imagen;
        }
    
        // Setters
        public function setCod_articulo($cod_articulo) {
            $this->cod_articulo = $cod_articulo;
        }
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        public function setPrecio($precio) {
            $this->precio = $precio;
        }
        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }
        public function setCategoria($categoria) {
            $this->categoria = $categoria;
        }
        public function setImagen($imagen) {
            $this->imagen = $imagen;
        }
        
        //METODOS/FUNCIONES DE LA CLASE QUE INCLUYEN CONEXIÓN A LA BD CON TRY CATCH

        //Funcion que genera automáticamente un código
        function generarCodigo() {
            
            $conn = conectar_DB();

            //Busca el último códifo insertado
            $query = $conn->query("SELECT MAX(cod_articulo) as ultimoCodigo FROM merchandising");
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $ultimoCodigo = $resultado['ultimoCodigo'];


            // Extrae el número actual
            $num = intval(substr($ultimoCodigo, 3));
        
            // Verifica si ya existe el número en la base de datos
            $codigoExistenteQuery = $conn->prepare("SELECT COUNT(*) as count FROM merchandising WHERE cod_articulo = :cod_articulo");
            $codigoExistenteQuery->bindParam(':cod_articulo', $ultimoCodigo);
            $codigoExistenteQuery->execute();
            $codigoExistenteResult = $codigoExistenteQuery->fetch(PDO::FETCH_ASSOC);
        
            if ($codigoExistenteResult['count'] > 0) {
                // Si el código ya existe, incrementa el número
                $num = ($num + 1) % 100000;
        
                // Verifica si el número alcanzó "99999" para poder incrementar las letras segun orden alfabetico
                if ($num == 0) {
                    // Incrementar las letras alfabéticamente
                    $letras = strtolower(substr($ultimoCodigo, 0, 3));
                    $ordenletras = ord($letras[2]);
        
                    if ($ordenletras == ord('z')) {
                        $ordenletras = ord('a');
                        $letras[1] = chr(ord($letras[1]) + 1);
                    } else {
                        $ordenletras++;
                    }
        
                    $letras[2] = chr($ordenletras);
        
                    $nuevoCodigo = $letras . '00000';
                } else {
                    // Controla los valores del número con 4 ceros a la izquierda y nos aseguramos que no excede los 5 digitos
                    $numControlado = sprintf('%05d', $num % 10000);
                    // Genera el nuevo código
                    $nuevoCodigo = 'aaa' . $numControlado;
                }
            }
        
            return $nuevoCodigo;
        }
        
        //Función para obtener la PK codigo
        public static function obtenerArticuloPorCodigo($cod_articulo) {
            
            $conn = conectar_DB();

            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT * FROM merchandising WHERE cod_articulo = :cod_articulo");
                    $stmt->bindParam(':cod_articulo', $cod_articulo);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        return new Articulo(
                            $row['cod_articulo'],
                            $row['nombre'],
                            $row['precio'],
                            $row['descripcion'],
                            $row['categoria'],
                            $row['imagen'],
                        );
                    } else {
                        return null;
                    }
                } catch (PDOException $e) {
                    echo "Error al buscar cliente: " . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
            return null;
        }

        //Función para modificar el articulo
        public static function modificarArticulo($cod_articulo, $nombre, $precio, $descripcion, $categoria, $imagen) {
            try {
                $conn = conectar_DB();
    
                // Preparar la consulta SQL
                $sql = "UPDATE merchandising SET nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria = :categoria, imagen = :imagen WHERE cod_articulo = :cod_articulo";
                $stmt = $conn->prepare($sql);
    
                // Vincular los parámetros
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':precio', $precio);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':categoria', $categoria);
                $stmt->bindParam(':imagen', $imagen);
                $stmt->bindParam(':cod_articulo', $cod_articulo);
    
                // Ejecutar la consulta
                $stmt->execute();
    
                // Verificar si la modificación fue exitosa
                $filasAfectadas = $stmt->rowCount();
    
                // Cerrar la conexión
                $conn = null;
    
                return $filasAfectadas > 0;
            } catch (PDOException $e) {
                // Manejar cualquier error de base de datos
                echo "Error al modificar el artículo desde la Clase: " . $e->getMessage();
                return false;
            }
        }

        //Funcion para eliminar el articulo
        public static function eliminarArticulo($cod_articulo) {
            
            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    // Elimina el artículo con el código proporcionado
                    $stmt = $conn->prepare("DELETE FROM merchandising WHERE cod_articulo = :cod_articulo");
                    $stmt->bindParam(':cod_articulo', $cod_articulo);
        
                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return false;
                    }
                } catch (PDOException $e) {
                    echo "Error al eliminar artículo: " . $e->getMessage();
                    return false;
                } finally {
                    $conn = null;
                }
            }
        }

        //Funcion para obtener el articulo por categoria
        public static function obtenerArticulosPorCategoria($categoria) {
            try {
                $conn = conectar_DB();
                $stmt = $conn->prepare("SELECT * FROM merchandising WHERE categoria = :cod_categoria");
                $stmt->bindParam(':cod_categoria', $cod_categoria);
                $stmt->execute();
                
                $articulos = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $articulos[] = new Articulo(
                        $row['cod_articulo'],
                        $row['nombre'],
                        $row['precio'],
                        $row['descripcion'],
                        $row['categoria'],
                        $row['imagen']
                    );
                }
                return $articulos;
            } catch (PDOException $e) {
                echo "Error al obtener artículos por categoría: " . $e->getMessage();
                return array();
            } finally {
                $conn = null;
            }
        }

        public static function obtenerArticulosPorNombre($nombre) {
            try {
                $conn = conectar_DB();
                $stmt = $conn->prepare("SELECT * FROM merchandising WHERE nombre = :nombre");
                $stmt->bindParam(':nombre', $nombre);
                $stmt->execute();
                
                $articulos = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $articulos[] = new Articulo(
                        $row['cod_articulo'],
                        $row['nombre'],
                        $row['precio'],
                        $row['descripcion'],
                        $row['categoria'],
                        $row['imagen']
                    );
                }
                return $articulos;
            } catch (PDOException $e) {
                echo "Error al obtener artículos por nombre: " . $e->getMessage();
                return array();
            } finally {
                $conn = null;
            }
        }
        
    }
?>
