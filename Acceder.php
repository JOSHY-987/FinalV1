<?php

session_start();
 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: Test.php");
    exit;
}
 
require_once "Inclusiones/Config.php";
 

$username = $password = "";
$username_err = $password_err = $login_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty($username_err) && empty($password_err)){
       
        $sql = "SELECT id, username, password, admin, nombre FROM usuarios WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
          
            if(mysqli_stmt_execute($stmt)){
               
                mysqli_stmt_store_result($stmt);
                
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                   
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $admin, $nombre);


                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){

  
                            if ($admin == 1){
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;  
                                $_SESSION["nombre"]  = $nombre;
                                
                                header("location: Administracion/Inicio.php");
                            }else{
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;   
                                $_SESSION["nombre"]  = $nombre;
                                 
                                header("location: Test.php");
                            }
                            
                           

                        } else{
                           
                            $login_err = "Usuario o contraseña incorrecta.";
                        }
                    }
                } else{
                  
                    $login_err = "Usuario o contraseña incorrecta.";
                }
            } else{
                echo "Conexion perdida.";
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

    <title>Acceder</title>

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

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido!</h1>
                                    </div>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="user">
                                        <?php 
                                            if(!empty($login_err)){
                                                echo '<div class="alert alert-danger">' . $login_err . '</div>';
                                            }        
                                        ?>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" name="username"
                                                aria-describedby="emailHelp"
                                                placeholder="Direccion de correo electronico...">
                                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                                                name="password" placeholder="Contraseña">
                                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Recuerdame</label>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <button type="submit" value="Login" class="btn btn-primary btn-user btn-block">
                                            Acceder
                                        </button>
                                        <br>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Ha olvidado su contraseña?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="Registrarse.php">Crear una cuenta!</a>
                                    </div>
                                </div>
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
    <script src="JavaScrpt/sb-admin-2.min.js"></script>

</body>

</html>