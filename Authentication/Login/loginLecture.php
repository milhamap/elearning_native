<?php
    session_start();
    require '../../function.php';
    
        if(isset($_COOKIE["nip"]) && isset($_COOKIE["key"])) {
            $nip = $_COOKIE["nip"];
            $key = $_COOKIE["key"];
            
            $result = mysqli_query($connect, "SELECT email FROM lectures WHERE nip = '$nip'");
            $row = mysqli_fetch_assoc($result);
    
            if($key === hash('sha256', $row["email"])){
                $_SESSION["login"] = true;
                $_SESSION["nip"] = $row["nip"];
            }
        }
    
        if(isset($_SESSION["login"])) {
            header("Location: ../../teacher/index.php");
            exit;
        }
    
        if(isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            
            $result = mysqli_query($connect, "SELECT * FROM lectures WHERE email = '$email'");
            
            if(mysqli_num_rows($result) === 1) {
                
                $row = mysqli_fetch_assoc($result);
                // var_dump($row);
                // die();
                // var_dump(password_verify($password, $row["password"]));
                // die();
                if(password_verify($password, $row["password"])) {
                    
                    $_SESSION["login"] = true;
                    $_SESSION["nip"] = $row["nip"];
                    if(isset($_POST["remember"])) {
                        setcookie('nip', $row["nip"], time()+60);
                        setcookie('key', hash('sha256', $row["email"]), time()+60);
                    }
                    
                    header("Location: ../../teacher/index.php");
                    exit;
                }
            }
            $error = true;
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

    <title>Login Lecture</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">
    <!-- <link href="/function.php" rel="stylesheet"> -->

</head>

<body class="bg-gradient-success">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                    <?php if (isset($error)) :?>
                        <p style="color: red; font-style: italic;">username / password salah</p>
                    <?php endif; ?>
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login Account As Lecture</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp" name="email"
                                                placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" name="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" name="remember">
                                                <label for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                            <button class="btn btn-success btn-user btn-block" type="submit" name="login">Login</button>
                                        <hr>
                                        <a href="index.php" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.php" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" data-toggle="modal" data-target="#registerModal">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="h4 text-gray-900 mb-4">CREATE AN ACCOUNT BASED ON</h1>
                </div>
                <div class="modal-body">
                    <a href="../Register/registerLecture.php" class="btn btn-warning btn-user btn-block">
                        REGISTER AS LECTURE
                    </a>
                    <a href="../Register/registerStudent.php" class="btn btn-danger btn-user btn-block">
                        REGISTER AS STUDENT
                    </a>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>
</body>

</html>