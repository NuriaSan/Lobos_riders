<?php
	// Iniciar una nueva sesión
	session_start();
	include_once("assets/db/conectar.php");
	include("claseUsuario.php");
?>

<!DOCTYPE html>
<html>

<head>
	<title>Login Page</title>
	<!--Made with love by Mutiullah Samim -->

	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
		integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="assets/css/style_login.css">
</head>

<body>


	<div class="container">
		<div class="d-flex justify-content-center h-100">
			<div class="card">
				<div class="card-header">
					<h3><a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/favicon-32x32.png" alt=""
								class="img-fluid"></a> Log In</h3>
					<div class="d-flex justify-content-end social_icon">
						<span><i class="fab fa-facebook-square"></i></span>
						<span><i class="fab fa-google-plus-square"></i></span>
						<span><i class="fab fa-twitter-square"></i></span>
					</div>
				</div>
				<div class="card-body">
					<form id="loginform" action="login.php" method="POST">
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="usuario" id="usuario" class="form-control" placeholder="&#128100; Usuario " required/>

						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" id="password" class="form-control" placeholder="&#128273; Password" required/>
						</div>
						<div class="form-group">
							<input type="submit" value="Login" class="btn float-right login_btn">
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-center links">
						¿No tienes cuenta?<a href="nuevousuario.php">Registrate</a>
					</div>
					<div class="d-flex justify-content-center">
						<a href="recuperarpassword.php">¿Has olvidado tu contraseña?</a>
					</div>
					<div class="d-flex justify-content-center">
						<a href="index.html">Volver a la página principal</a>
					</div>
				</div>
				<?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $usuario = $_POST["usuario"];
                        $password = $_POST["password"];

                        // Validar el usuario y la contraseña
                        $usuarioObj = Usuario::nombreClave($usuario, $password);
                        $estadoAdmin = Usuario::obtenerAdminPorUsuario($usuario);
                        $estadoSocio = Usuario::obtenerSocioPorUsuario($usuario);

                        if ($usuarioObj) {
                            $_SESSION["autentificado"] = true;
                            $_SESSION["usuario"] = $usuarioObj;

                            if ($estadoAdmin == 1) {
                                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionadmin.php?usuario="; }, 0);</script>';
                                exit();
                            } elseif ($estadoSocio == 1) {
                                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionsocio.php?usuario="; }, 0);</script>';
                                exit();
                            } else {
                                echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "sesionusuario.php?usuario="; }, 0);</script>';
                                exit();
                            }
                        } else {
                            // Establecer mensaje de error
                            echo '<div class="alert alert-danger f" role="alert">Usuario o contraseña incorrectos.</div>';
                        }
                    }
                    ?>
			</div>
		</div>
	</div>
	
</body>

</html>