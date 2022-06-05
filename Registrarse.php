<?php

require_once "Inclusiones/Config.php";
 

$username = $nombre = $password = $confirm_password = "";
$username_err = $nombre_err = $password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Porfavor introduzca un correo.";
    } 
    else{
        
        $sql = "SELECT id FROM usuarios WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Este correo ya existe.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Porfavor ingrese una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Porfavor confirme su contraseña";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }
    
    if(empty(trim($_POST["nombre"]))){
        $nombre_err = "Porfavor introduce un nombre.";     
    } 
    else{
        $nombre = trim($_POST["nombre"]);
    }
   
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nombre_err)){
        

        $sql = "INSERT INTO usuarios (username, password,nombre) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){

            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password , $param_nombre);
            
          
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_nombre = $nombre;
           
            if(mysqli_stmt_execute($stmt)){
              
                header("location: Acceder.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

           
            mysqli_stmt_close($stmt);
        }
    }
    
 
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registrarse</title>

    <!-- Custom fonts for this template-->
    <link href="Proveedores/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="CSS/Admin.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crear una nueva cuenta!</h1>
                            </div>
                            <form class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>" name="nombre"
                                        placeholder="Nombre Completo">
                                        <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" name="username"
                                        placeholder="Direccion de correo electronico">
                                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"
                                            name="password" placeholder="Contraseña">
                                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"
                                            name="confirm_password" placeholder="Repita Contraseña">
                                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                </div>
                                <br>
                                <input type="submit" class="btn btn-primary btn-user btn-block" value="Registrarse">
                                <br>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="#">Ha olvidado su contraseña?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="Acceder.php">Ya posee una cuenta? Acceda!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="Proveedores/jquery/jquery.min.js"></script>
    <script src="Proveedores/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="Proveedores/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="JavaScript/sb-admin-2.min.js"></script>

</body>

</html>