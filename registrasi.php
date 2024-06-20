<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "gna_store");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G.N.A.ID Hijab Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: white;
            flex-direction: column;
        }

        .container {
            max-width: 600px;
            padding: 50px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            background-color: white;
            text-align: center;
            height: 630px;
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .login-logo {
            margin-bottom: 20px;
            position: absolute;
            top: 20px;
            width: 100%;
            text-align: center;
        }

        .login-logo img {
            width: 280px;
        }

        .alert {
            margin-top: 20px;
        }

        .btn-black {
            background-color: black;
            color: white;
        }

        .container h4 {
            margin-bottom: 30px; /* Adjust spacing as needed */
        }
		
		.eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

    </style>
</head>

<body>

<div class="login-logo">
        <img src="gambar/logojadi.jpg" alt="Logo G.N.A.ID">
    </div>

<div class="container">
    <h4 class="text-center">REGISTRASI AKUN</h4>
    <?php
    if (isset($_POST["submit"])) {
        $fullname = $_POST["fullname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["ulangi_password"];
        $no_telepon = $_POST["no_telepon"];
        $tgl_lahir = $_POST["tgl_lahir"];
        $jenis_kelamin = $_POST["jk"];
        $provinsi = $_POST["provinsi"];
        $kecamatan = $_POST["kecamatan"];
        $kota = $_POST["kota"];
        $alamat_lengkap = $_POST["alamat_lengkap"];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $errors = array();

        if (empty($fullname) || empty($email) || empty($password) || empty($passwordRepeat) || empty($no_telepon) || empty($tgl_lahir) || empty($jenis_kelamin) || empty($provinsi) || empty($kecamatan) || empty($kota) || empty($alamat_lengkap)) {
            array_push($errors, "Harap Isi Data dengan Benar");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email tidak valid");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password minimal 8 karakter");
        }
        if ($password !== $passwordRepeat) {
            array_push($errors, "Password tidak sama");
        }

        require_once "db_connect.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            array_push($errors, "Email sudah ada!");
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            $sql = "INSERT INTO users (nama_lengkap, email, password, no_telepon, tanggal_lahir, jenis_kelamin, provinsi, kecamatan, kota, alamat_lengkap) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "ssssssssss", $fullname, $email, $passwordHash, $no_telepon, $tgl_lahir, $jenis_kelamin, $provinsi, $kecamatan, $kota, $alamat_lengkap);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>Kamu Telah Berhasil Registrasi</div>";
                
                // Set session and redirect to index.php
                $_SESSION['user_id'] = $mysqli->insert_id;
                $_SESSION['user_email'] = $email;
                header("Location: index.php");
                exit();
            } else {
                die("UPS! Terjadi kesalahan");
            }
        }
    }
    ?>
    <form action="registrasi.php" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="fullname" placeholder="Nama Lengkap">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="no_telepon" placeholder="Nomor Telepon">
        </div>
        <div class="form-group">
			<input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir">
		</div>
        <div class="form-group">
			<select class="form-control" name="jk" id="jk">
				<option value="" disabled selected>Jenis Kelamin</option>
				<option value="Laki-laki">Laki-laki</option>
				<option value="Perempuan">Perempuan</option>
			</select>
		</div>
		<div class="form-group">
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"></i></span>
                </div>
            </div>
		<div class="form-group">
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="ulangi_password" placeholder="Repeat Password" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"></i></span>
                </div>
            </div>
        <div class="form-group">
            <input type="text" class="form-control" name="provinsi" placeholder="Provinsi">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="kota" placeholder="Kota">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="kecamatan" placeholder="Kecamatan">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="alamat_lengkap" placeholder="Alamat Lengkap">
        </div>
		<div class="form-group">
			<input type="text" class="form-control" name="kode_pos" placeholder="Kode Pos">
		</div>
        <button class="btn btn-black btn-block" onclick="window.location.href='index.php'">Kembali</button>
        <button type="submit" class="btn btn-black btn-block" name="submit">Registrasi Sekarang</button>
    </form>
</div>
</body>
</html>