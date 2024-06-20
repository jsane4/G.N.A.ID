<?php
include 'db_connect.php';
include 'session.php';

$emailuser = $_GET['id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_users='$emailuser'");
$user = mysqli_fetch_assoc($user_query);

$pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_users='$emailuser'");
$cart_items = [];
$total = 0;

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
$total_amount = $subtotal + $shipping_cost;

$_SESSION['checkout_details'] = [
    'subtotal' => $subtotal,
    'service_fee' => $service_fee,
    'total_amount' => $total_amount,
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 40px;
            background-color: #FFFFFF;
            border-bottom: 1px solid #ddd;
        }
        header .logo img {
            width: 282px;
            height: 118px;
            border-radius: 30px;
        }
        header .search-bar {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }
        .search-bar form {
            display: flex;
            width: 100%;
            max-width: 600px;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            outline: none;
        }
        .search-bar button[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #000000;
            color: white;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #b6a9a9;
        }
        header .icons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        header .icons a {
            margin-left: 20px;
            position: relative;
        }
        header .icons a:not(:first-child)::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 0;
            bottom: 0;
            width: 1px;
            background-color: #ccc;
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
            margin-top: 20px; /* Adding margin top to create space between header and container */
        }
        .cart-items h3, .shipping-details h3 {
            margin-bottom: 20px;
        }
        .cart-items table, .shipping-details form {
            width: 100%;
        }
        .cart-items th, .cart-items td, .shipping-details label {
            padding: 10px;
            text-align: left;
        }
        .cart-items th {
            background-color: #f4f4f4;
        }
        .shipping-details {
            margin-top: 20px;
        }
        .shipping-details label {
            display: block;
            margin-bottom: 5px;
        }
        .shipping-details input, .shipping-details textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .shipping-options {
            margin-top: 20px;
        }
        .shipping-options label {
            margin-right: 20px;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .cart-actions button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #000000;
            color: white;
            border-radius: 5px;
        }
        .whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>

<?php require "header.php"; ?>

    <div class="cart-nav">
        <div <?php if (basename($_SERVER['PHP_SELF']) == 'keranjang.php') echo 'class="active"'; ?>>Keranjang Pesanan</div>
        <div <?php if (basename($_SERVER['PHP_SELF']) == 'checkout.php') echo 'class="active"'; ?>>Checkout</div>
        <div <?php if (basename($_SERVER['PHP_SELF']) == 'pembayaran.php') echo 'class="active"'; ?>>Pembayaran</div>
        <div <?php if (basename($_SERVER['PHP_SELF']) == 'selesai.php') echo 'class="active"'; ?>>Selesai</div>
    </div>
    
    <div class="container">
        <div class="cart-items">
            <h3>Produk Dalam Keranjang</h3>
            <table class="table table-bordered table-hover">
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
                <tr>
                    <th colspan="3">Subtotal</th>
                    <th>Rp<?php echo number_format($subtotal, 2); ?></th>
                </tr>
                <tr>
                    <th colspan="3">Biaya Admin</th>
                    <th>Rp<?php echo number_format($service_fee, 2); ?></th>
                </tr>
                <tr>
                    <th colspan="3">Total Pembayaran</th>
                    <th id="totalAmount">Rp<?php echo number_format($total_amount, 2); ?></th>
                </tr>
            </table>
        </div>

        <div class="shipping-details">
            <h3>DETAIL PENGIRIMAN</h3>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $user['nama_lengkap']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo $user['no_telepon']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                    <input type="text" class="form-control" id="alamat_lengkap" name="alamat_lengkap" value="<?php echo $user['alamat_lengkap']; ?>" readonly>
                </div>
            </form>    

            <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
                <button id="lanjutBayarButton" style="margin-bottom: 10px;">Lanjut Bayar</button>
                <button id="kembaliButton">Kembali</button>
            </div>
		</div>
    </form>
</div>
                    </div>

<?php require "footer.php"; ?>

<?php require "whatsapp-button.php" ?>
	
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('lanjutBayarButton').onclick = function(event) {
        event.preventDefault();
        window.location.href = 'pembayaran.php?id=<?php echo isset($emailuser1['id_users']) ? $emailuser1['id_users'] : ''; ?>';
    };
    
    document.getElementById('kembaliButton').onclick = function(event) {
        event.preventDefault();
        window.location.href = 'keranjang.php?id=<?php echo isset($emailuser1['id_users']) ? $emailuser1['id_users'] : ''; ?>';
    };
</script>
</body>
</html>