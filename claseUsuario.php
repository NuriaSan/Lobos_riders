<?php

    class Usuario {
        private $dni;
        private $usuario;
        private $nombre;
        private $telefono;
        private $email;
        private $password;
        private $admin;
        private $socio;

        public function __construct($dni, $usuario,  $nombre, $telefono, $email, $password, $admin, $socio) {
            $this->dni = $dni;
            $this->usuario = $usuario;
            $this->nombre = $nombre;
            $this->telefono = $telefono;
            $this->email = $email;
            $this->password = $password;
            $this->admin = $admin;
            $this->socio = $socio;
        }

        // Getters
        public function getDni() {
            return $this->dni;
        }
        public function getUsuario() {
            return $this->usuario;
        }
        public function getNombre() {
            return $this->nombre;
        }
        public function getTelefono() {
            return $this->telefono;
        }
        public function getEmail() {
            return $this->email;
        }
        public function getPassword() {
            return $this->password;
        }
        public function getAdmin() {
            return $this->admin;
        }
        public function getSocio() {
            return $this->socio;
        }
       

        // Setters
        public function setUsuario($usuario) {
            $this->usuario = $usuario;
        }
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        public function setTelefono($telefono) {
            $this->telefono = $telefono;
        }
        public function setEmail($email) {
            $this->email = $email;
        }
        public function setPassword($password) {
            $this->password = $password;
        }
        public function setAdmin($admin) {
            $this->admin = $admin;
        }
        public function setSocio($socio) {
            $this->socio = $socio;
        }
        
        //METODOS/FUNCIONES DE LA CLASE QUE INCLUYEN CONEXIÓN A LA BD CON TRY CATCH

        //Funcion para obtener el dni con usuario
        public static function obtenerDniPorUsuario($usuario) {
            
            $conn = conectar_DB();
    
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT dni FROM usuarios WHERE usuario = :usuario");
                    $stmt->bindParam(':usuario', $usuario);
                    $stmt->execute();
    
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        return $row['dni'];
                    } else {
                        return null; 
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener DNI del usuario: " . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
            return null; 
        }



        // Función para obtener usuario con DNI
        public static function obtenerUsuarioPorDNI($dni) {
            
            $conn = conectar_DB();

            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE dni = :dni");
                    $stmt->bindParam(':dni', $dni);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        return new Usuario(
                            $row['dni'],
                            $row['usuario'],
                            $row['nombre'],
                            $row['telefono'],
                            $row['email'],
                            $row['password'],
                            $row['admin'],
                            $row['socio']
                            
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

        //Funcion para obtener informacion de Admin con usuario
        public static function obtenerAdminPorUsuario($usuario) {
            
            $conn = conectar_DB();
    
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT admin FROM usuarios WHERE usuario = :usuario");
                    $stmt->bindParam(':usuario', $usuario);
                    $stmt->execute();
    
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        return $row['admin'];
                    } else {
                        return null; 
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener el estado de Admin del usuario: " . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
            return null; 
        }

        //Funcion para obtener informacion de Socio con usuario
        public static function obtenerSocioPorUsuario($usuario) {
            
            $conn = conectar_DB();
    
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT socio FROM usuarios WHERE usuario = :usuario");
                    $stmt->bindParam(':usuario', $usuario);
                    $stmt->execute();
    
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        return $row['socio'];
                    } else {
                        return null; 
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener el estado de Socio del usuario: " . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
            return null; 
        }

        //Funcion nombre Clave para el login
        public static function nombreClave($usuario, $password) {
            
            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
                    $stmt->bindParam(':usuario', $usuario);
                    $stmt->execute();

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row && password_verify($password, $row['password'])) {
                        return new Usuario(
                            $row['dni'],
                            $row['usuario'],
                            $row['nombre'],
                            $row['telefono'],
                            $row['email'],
                            $row['password'],
                            $row['admin'],
                            $row['socio']
                        );
                    }
                    
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
        
            return null;
        }

        // Función para registrar un nuevo usuario
        public static function registrarUsuario($dni, $usuario, $nombre, $telefono, $email, $password, $admin, $socio) {
            
            $conn = conectar_DB();
            
            if ($conn) {
                try {
                    // comprueba si existe ya el nombre de usuario con una función creada para localizarlo
                    if (self::usuarioExistente($usuario)) {
                        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">El nombre de usuario ya se encuentra registrado. Inténtelo de nuevo con otro nombre</div>';
                        return false; 
                    }
                    if (self::dniExistente($dni)) {
                        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">El DNI ya se encuentra registrado. Inténtelo con un DNI nuevo.</div>';
                        return false; 
                    }
            
                    $stmt = $conn->prepare("INSERT INTO usuarios (dni, usuario, nombre, telefono, email, password, admin, socio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bindParam(1, $dni, PDO::PARAM_STR);
                    $stmt->bindParam(2, $usuario, PDO::PARAM_STR);
                    $stmt->bindParam(3, $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(4, $telefono, PDO::PARAM_INT);
                    $stmt->bindParam(5, $email, PDO::PARAM_STR);
                    $stmt->bindParam(6, $password, PDO::PARAM_STR);
                    $stmt->bindParam(7, $admin, PDO::PARAM_INT);
                    $stmt->bindParam(8, $socio, PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success text-center" role="alert" style="font-size: 30px; margin-top: 50px;">
                                Inserción de Datos correcta.
                            </div>';
                        return true;
                    } else {
                        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error al insertar los datos.</div><br><br>';
                    }
                } catch (PDOException $e) {
                    echo "Error al preparar o ejecutar la consulta: " . $e->getMessage() . "<br><br>";
                } finally {
                    $conn = null;
                }
            }
            
            return false;
        }
        
        
        // Función que nos busca si existe el nombre de usuario insertado en la base de datos
        private static function usuarioExistente($usuario) {

            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
                    $stmt->bindParam(':usuario', $usuario);
                    $stmt->execute();
        
                    return ($stmt->fetchColumn() > 0);
                } catch (PDOException $e) {
                    echo "Error al verificar la existencia del usuario: " . $e->getMessage() . "<br><br>";
                } finally {
                    $conn = null;
                }
            }
        
            return false;
        }

        private static function dniExistente($dni) {

            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = :dni");
                    $stmt->bindParam(':dni', $dni);
                    $stmt->execute();
        
                    return ($stmt->fetchColumn() > 0);
                } catch (PDOException $e) {
                    echo "Error al verificar la existencia del dni insertado: " . $e->getMessage() . "";
                } finally {
                    $conn = null;
                }
            }
            return false;
        }

        //Función Eliminar
        public static function eliminarUsuario($dni) {

            $conn = conectar_DB();
            
            if ($conn) {
                try {
                    // Elimina al usuario con el DNI proporcionado
                    $stmt = $conn->prepare("DELETE FROM usuarios WHERE dni = :dni");
                    $stmt->bindParam(':dni', $dni);
                    
                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return false;
                    }
                } catch (PDOException $e) {
                    echo "Error al eliminar usuario: " . $e->getMessage();
                    return false;
                } finally {
                    $conn = null;
                }
            }
            return false;
        }
        
        //Funcion Modificar
        public static function modificarUsuario($dni, $nombre, $telefono, $email, $nuevaPassword = null) {
            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    // Prepara la consulta para actualizar los detalles del usuario en la base de datos
                    if ($nuevaPassword) {
                        // Si se proporciona una nueva contraseña, actualiza también la contraseña
                        $hashedNuevaPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE usuarios SET nombre = :nombre, telefono = :telefono, email = :email, password = :password WHERE dni = :dni");
                        $stmt->bindParam(':password', $hashedNuevaPassword);
                    } else {
                        // Si no se proporciona una nueva contraseña, actualiza solo los demás detalles
                        $stmt = $conn->prepare("UPDATE usuarios SET nombre = :nombre, telefono = :telefono, email = :email WHERE dni = :dni");
                    }
        
                    // Enlaza los parámetros
                    $stmt->bindParam(':dni', $dni);
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':telefono', $telefono);
                    $stmt->bindParam(':email', $email);
        
                    // Ejecuta la consulta
                    return $stmt->execute();
                } catch (PDOException $e) {
                    echo "Error al modificar usuario: " . $e->getMessage();
                    return false;
                } finally {
                    $conn = null;
                }
            }
        }

        //Funcion para actualizar la contraseña
        public function actualizarPassword($nuevoPassword) {
            $hashNuevoPassword = password_hash($nuevoPassword, PASSWORD_DEFAULT);

            $conn = conectar_DB();
    
            if ($conn) {
                try {
                    $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE dni = :dni");
                    $stmt->bindParam(':password', $hashNuevoPassword);
                    $stmt->bindParam(':dni', $this->dni);
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo "Error al actualizar la contraseña: " . $e->getMessage();
                } finally {
                    $conn = null;
                }
            }
        }
        
        //Función que cuenta la cantidad de Administradores existentes
        public static function contarAdministradores() {
            try {
                $conn = conectar_DB();
    
                if (!$conn) {
                    die("Error al conectar a la base de datos");
                }
    
                // Consulta SQL que cuenta la cantidad de administradores
                $sql = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE admin = 1";
    
                $stmt = $conn->prepare($sql);
                $stmt->execute();
    
                // Resultado de la cantidad obtenida de la consulta
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $conn = null;
    
                // Devolver la cantidad de administradores
                return isset($result['cantidad']) ? $result['cantidad'] : 0;
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
                return 0;
            }
        }
        public static function registrarDatosEnvio($usuario, $nombre, $apellidos, $direccion, $localidad, $provincia, $cp, $pais) {
            
            $conn = conectar_DB();
            
            if ($conn) {
                try {
            
                    $stmt = $conn->prepare("INSERT INTO usuario_datosenvio (usuario, nombre, apellidos, direccion, localidad, provincia, cp, pais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bindParam(1, $usuario, PDO::PARAM_STR);
                    $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
                    $stmt->bindParam(4, $direccion, PDO::PARAM_STR);
                    $stmt->bindParam(5, $localidad, PDO::PARAM_STR);
                    $stmt->bindParam(6, $provincia, PDO::PARAM_STR);
                    $stmt->bindParam(7, $cp, PDO::PARAM_INT);
                    $stmt->bindParam(8, $pais, PDO::PARAM_STR);
                    
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success text-center" role="alert" style="font-size: 30px; margin-top: 50px;">
                                Inserción de Datos correcta.
                            </div>';
                        echo "<script>window.location.href = 'Pagos.php';</script>";
                    } else {
                        echo '<div style="color: red; text-align: center; font-size: 30px; margin-top: 50px;">Error al insertar los datos.</div><br><br>';
                    }
                } catch (PDOException $e) {
                    echo "Error al preparar o ejecutar la consulta: " . $e->getMessage() . "<br><br>";
                } finally {
                    $conn = null;
                }
            }
        
        }
        public static function modificarDatosEnvio($usuario, $nombre, $apellidos, $direccion, $localidad, $provincia, $cp, $pais) {
            $conn = conectar_DB();
        
            if ($conn) {
                try {
                    
                    $stmt = $conn->prepare("UPDATE usuario_datosenvio SET nombre = :nombre, apellidos = :apellidos, direccion = :direccion, localidad = :localidad, provincia = :provincia, cp = :cp, pais = :pais WHERE usuario = :usuario");
                        
            
                        // Vincular los valores a los marcadores de posición
                        $stmt->bindParam(':nombre', $nombre);
                        $stmt->bindParam(':apellidos', $apellidos);
                        $stmt->bindParam(':direccion', $direccion);
                        $stmt->bindParam(':localidad', $localidad);
                        $stmt->bindParam(':provincia', $provincia);
                        $stmt->bindParam(':cp', $cp);
                        $stmt->bindParam(':pais', $pais);
                        $stmt->bindParam(':usuario', $usuario);
                    
                    // Ejecutar la consulta
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo "Error al actualizar los datos de envio: " . $e->getMessage();
                    return false;
                } finally {
                    $conn = null;
                }
            }
        }
        public static function obtenerDatosEnvio($usuario) {
            try {
                $conn = conectar_DB();
                $stmt = $conn->prepare("SELECT * FROM usuario_datosenvio WHERE usuario = :usuario");
                $stmt->bindParam(':usuario', $usuario);
                $stmt->execute();
                
                $datos_envio = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $datos_envio[] = array(
                        'nombre' => $row['nombre'],
                        'apellidos' => $row['apellidos'],
                        'direccion' => $row['direccion'],
                        'localidad' => $row['localidad'],
                        'provincia' => $row['provincia'],
                        'cp' => $row['cp'],
                        'pais' => $row['pais']
                    );
                }
                
                // Cerrar conexión y retornar los datos de envío
                $conn = null;
                return $datos_envio;
            } catch (PDOException $e) {
                echo "Error al obtener datos de envío: " . $e->getMessage();
                return array(); // Retornar un array vacío en caso de error
            }
        }
    }

?>