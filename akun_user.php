<?php
require "db_connect.php";
require 'session.php';

$emailuser = $_GET['id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_users='$emailuser'");
$user = mysqli_fetch_assoc($user_query);

$order_query = mysqli_query($conn, "SELECT no_resi FROM orders WHERE id_users='$emailuser' ORDER BY id_users DESC LIMIT 1");
$order = mysqli_fetch_assoc($order_query);
$no_resi = $order['no_resi'] ?? 'Belum ada resi';
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman Akun Pengguna</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .container {
            padding: 20px;
            margin-top: 20px;
        }

        .info-container {
            width: 70%;
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="text"]:disabled {
            background-color: #f9f9f9;
        }

        .btn,
        .btn-secondary {
            padding: 10px 20px;
            border: none;
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        .btn:hover,
        .btn-secondary:hover {
            background-color: #808080;
            color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        </style>
    </head>

    <body>

        <?php require "header.php"; ?>

         <div class="header">
            <h1>AKUN SAYA</h1>
        </div>

        <div class="container">
            <div class="form-group">
                <label for="fullname">Email</label>
                <input type="text" id="email" value="<?php echo $user['email']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" id="fullname" value="<?php echo $user['nama_lengkap']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="phone">No Telepon</label>
                <input type="text" id="phone" value="<?php echo $user['no_telepon']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="birthdate">Tanggal Lahir</label>
                <input type="text" id="birthdate" value="<?php echo $user['tanggal_lahir']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <input type="text" id="gender" value="<?php echo $user['jenis_kelamin']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="province">Provinsi</label>
                <input type="text" id="province" value="<?php echo $user['provinsi']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="city">Kota</label>
                <input type="text" id="city" value="<?php echo $user['kota']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="district">Kecamatan</label>
                <input type="text" id="district" value="<?php echo $user['kecamatan']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="address">Alamat Lengkap</label>
                <input type="text" id="address" value="<?php echo $user['alamat_lengkap']; ?>" disabled>
            </div>
            <button class="btn" onclick="window.location.href='index.php'">Kembali</button>
        </div>

    </body>

</html>