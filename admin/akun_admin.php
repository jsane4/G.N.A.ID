<?php
require "session.php";
require "koneksi.php";

$mysqli = new mysqli("localhost", "root", "", "gna_store");

$username = $_SESSION['username'];
$user = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
$userlogin = mysqli_fetch_assoc($user);

$query = "SELECT * FROM toko WHERE id=1"; 
$result = mysqli_query($mysqli, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $toko = mysqli_fetch_assoc($result);
    $namaToko = $toko['nama'];
    $logoPath = $toko['logo_path'];
} else {
    $namaToko = "Nama Toko Default";
    $logoPath = "../gambar/logojadi.jpg";
}

$totalTransferQuery = "SELECT SUM(total_bayar) AS total_transfer FROM orders WHERE status_pesanan = 'paid'";
$totalTransferResult = mysqli_query($mysqli, $totalTransferQuery);
$totalTransferRow = mysqli_fetch_assoc($totalTransferResult);
$totalTransfer = $totalTransferRow['total_transfer'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }
        .container {
            display: flex;
            max-width: 1200px;
            margin: auto;
            padding-top: 20px;
        }
        .sidebar {
            width: 200px; 
            padding: 20px;
            background-color: #f0f0f0; 
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .main-content {
            flex: 1; 
            padding: 20px;
            background-color: #ffffff; 
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-left: 20px; 
        }
        .sidebar .nav-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            text-align: left;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .sidebar .nav-button.active {
            background-color: #0e4c92;
            color: #fff;
        }
        .sidebar .nav-button:hover {
            background-color: #e9ecef;
            color: #000;
        }
        .main-content .section {
            display: none;
        }
        .main-content .section.active {
            display: block;
        }
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 40px;
            background-color: #FFFFFF;
            border-bottom: 1px solid #ddd;
        }
        .icon-menu {
                display: flex;
                align-items: center;
                cursor: pointer;
            }
        .cart-actions button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #0e4c92;
            color: white;
            border-radius: 5px;
        }
        .cart-actions button:hover {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #e9ecef;
            color: black;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <?php require "header.php"; ?>

    <div class="header">
        <h1>AKUN ADMIN</h1>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <div class="nav-button" id="categoryBtn" onclick="showSection('categorySection')">Kelola Kategori</div>
            <div class="nav-button" id="productBtn" onclick="showSection('productSection')">Kelola Produk</div>
            <div class="nav-button" id="bannerBtn" onclick="showSection('bannerSection')">Kelola Banner</div>
        </div>
        <div class="main-content">
            <div class="section" id="categorySection">
                <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
                <button onclick="window.location.href='kategori.php'">Kelola Kategori</button>
                </div>
            </div>
            <div class="section" id="productSection">
                <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
                <button onclick="window.location.href='produk.php'">Kelola Produk</button>
                </div>
            </div>
            <div class="section" id="bannerSection">
                <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
                <button onclick="window.location.href='banner.php'">Kelola Banner</button>
                </div>
            </div>
    <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
    <button onclick="window.location.href='index.php'"style="margin-top: 10px;">Kembali</button>
    </div>
        </div>
    </div>


    <?php require "footer.php"; ?>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.main-content .section').forEach(function(section) {
                section.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.sidebar .nav-button').forEach(function(button) {
                button.classList.remove('active');
            });

            // Show the selected section
            document.getElementById(sectionId).classList.add('active');

            // Add active class to the clicked button
            document.getElementById(sectionId.replace('Section', 'Btn')).classList.add('active');
        }

        // Show the default section on page load
        showSection('ordersSection');
    </script>
</body>
</html>