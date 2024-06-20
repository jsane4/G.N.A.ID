<?php
include 'db_connect.php';
include 'session.php';

$emailuser = $_GET['id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_users='$emailuser'");
$user = mysqli_fetch_assoc($user_query);

$toko_query = mysqli_query($conn, "SELECT * FROM toko");
$toko = mysqli_fetch_assoc($toko_query);

$pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_users='$emailuser'");
$cart_items = [];
$total = 0;

$payment_method = isset($_SESSION['checkout_details']['payment_method']) ? $_SESSION['checkout_details']['payment_method'] : 'Belum dipilih';
$payment_info = isset($_SESSION['checkout_details']['payment_info']) ? $_SESSION['checkout_details']['payment_info'] : '';

while ($qpesanan = mysqli_fetch_array($pesanan)) {
    $produk_query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='" . $qpesanan['id_produk'] . "'");
    $qproduk = mysqli_fetch_assoc($produk_query);

    $jumlah = $qpesanan['kuantitas'] * $qproduk['harga'];
    $cart_items[] = [
        'name' => $qproduk['nama_produk'],
        'price' => $qproduk['harga'],
        'quantity' => $qpesanan['kuantitas'],
        'subtotal' => $jumlah
    ];
    $total += $jumlah;
}

$subtotal = $total;
$shipping_cost = 0; 
$service_fee = 1000;    
$total_amount = $subtotal + $shipping_cost + $service_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
        }
        .container {
            margin-top: 20px;
        }
        .section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .cart-nav {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            background-color: #e8e8e8;
            border-bottom: 1px solid #ddd;
        }
        .cart-nav div {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 20px;
        }
        .cart-nav div.active {
            background-color: #000000;
            color: #fff;
        }

        .cart-details {
            padding: 70px;
        }
        .cart-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-details table, th, td {
            border: 1px solid #ddd;
        }
        .cart-details th, .cart-details td {
            padding: 10px;
            text-align: center;
        }
        .cart-summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .cart-summary div {
            flex: 1;
            padding: 10px;
            text-align: center;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
        }
        .cart-actions button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #000000;
            color: white;
            border-radius: 5px;
        }
        header .logo {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }
        header .logo img {
            width: 282px;
            height: 118px;
            border-radius: 30px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php">
            <img src="gambar/logojadi.jpg" alt="Logo G.N.A.ID">
        </a>
    </div>
</header>

<div class="container">

    <div class="section">
        <h2>Penerima</h2>
        <p><strong>Nama :</strong> <?php echo $user['nama_lengkap']; ?></p>
        <p><strong>No. HP :</strong> <?php echo $user['no_telepon']; ?></p>
        <p><strong>Alamat Kirim :</strong> <?php echo $user['alamat_lengkap']; ?></p>
        <div class="alert alert-warning" role="alert">
        <p><strong>No. Resi :</strong> No resi akan diupdate di halaman akunmu.</p>
        </div>
    </div>

    <div class="section">
        <h2>Detail Barang yang Dipesan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>Rp<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>Rp<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">
            <div>
                <p>Total Harga : Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                <p>Biaya Admin G.N.A.ID : Rp. <?php echo number_format($service_fee, 0, ',', '.'); ?></p>
                <p id="metodePembayaran">Metode Pembayaran : <?php echo htmlspecialchars($payment_method); ?></p>
                <?php if ($payment_info): ?>
                    <div class="alert alert-info" role="alert">
                        <p><strong>Harap Transfer ke :</strong> <?php echo htmlspecialchars($payment_info); ?></p>
                        <p><strong>Total Yang Harus Dibayar :</strong> Rp. <?php echo number_format($total_amount, 0, ',', '.'); ?></p>
                        <p><strong>Agar pesanan dapat diproses, konfirmasi pembayaranmu ke nomor :</strong> <?php echo $toko['no_telepon']; ?></p>
                        <p><strong>Dan sertakan nota ini!</strong></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.print();
    window.onafterprint = function() {
        window.location.href = 'index.php';
    };
</script>
</body>
</html>
