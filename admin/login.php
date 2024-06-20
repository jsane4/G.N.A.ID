<?php
session_start();
require "koneksi.php";

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>G.N.A.ID Hijab Store - ADMIN</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: white;
                margin: 0;
            }

            .login-logo {
                text-align: center;
                margin-bottom: 20px;
            }

            .login-logo img {
                width: 280px;
            }

            .login-container {
                background-color: #f8f9fa;
                padding: 50px;
                border-radius: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                /* Center the content inside the container */
            }

            .login-container h4 {
                margin-bottom: 30px;
                /* Adjust spacing as needed */
            }

            .form-group {
                margin-bottom: 20px;
            }

            .btn-black {
                background-color: black;
                color: white;
            }

            .eye-icon {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
            }
        </style>
        </style>
    </head>

    <body>
        <header class="d-flex justify-content-between align-items-center p-3">
            <div>
                <div class="login-logo">
                    <img src="../gambar/logojadi.jpg" alt="Logo G.N.A.ID">
                </div>
                <div class="login-container">
                    <h4 class="text-center">LOGIN ADMIN</h4>

                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword"
                                        style="cursor: pointer;"></i></span>
                            </div>
                        </div>
                        <button name="loginbtn" type="submit" class="btn btn-black btn-block">Login</button>
                    </form>
                    <?php
                    if (isset($_POST['loginbtn'])) {
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        $query = mysqli_query($con, "SELECT * FROM admin WHERE email='$email' OR username='$email' AND password='$password'");

                        $count = mysqli_num_rows($query);
                        $data = mysqli_fetch_array($query);

                        if ($count > 0) {
                            $_SESSION['username'] = $data['username'];
                            $_SESSION['login'] = true;
                            ?>
                            <div class="alert alert-primary mt-3" role="alert">
                                Login Berhasil
                            </div>
                            <meta http-equiv="refresh" content="2; url=index.php" />
                            <?php
                        } else { ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                Username atau Password Salah
                            </div>
                            <meta http-equiv="refresh" content="2; url=index.php" />
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                document.getElementById('togglePassword').addEventListener('click', function () {
                    const password = document.getElementById('password');
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('bi-eye');
                    this.classList.toggle('bi-eye-slash');
                });
            </script>
    </body>

</html>