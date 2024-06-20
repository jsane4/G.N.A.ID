<?php
include 'db_connect.php';
include 'session.php';

$emailuser = $_GET['id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_users='$emailuser'");
$user = mysqli_fetch_assoc($user_query);

$pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_users='$emailuser'");
$cart_items = [];
$total = 0;

$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$payment_info = isset($_POST['payment_info']) ? $_POST['payment_info'] : '';

if ($payment_method && $payment_info) {
    $_SESSION['checkout_details']['payment_method'] = $payment_method;
    $_SESSION['checkout_details']['payment_info'] = $payment_info;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['payment_method'])) {
        $_SESSION['checkout_details']['payment_method'] = $_POST['payment_method'];
    }
}

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

if (isset($_SESSION['checkout_details'])) {
    $subtotal = $_SESSION['checkout_details']['subtotal'];
    $service_fee = $_SESSION['checkout_details']['service_fee'];
    $total_amount = $_SESSION['checkout_details']['total_amount'];
} else {
    $subtotal = 0;
    $service_fee = 0;
    $total_amount = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lanjutkan'])) {
    header("Location: transaksi_selesai.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pembayaran</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
            padding: 20px;
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
        .section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h3 {
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .metode-pembayaran-container {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .total-pembayaran-container {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
    </style>
<body>

    <?php require "header.php"; ?>

    <div class="cart-nav">
        <div <?php if(basename($_SERVER['PHP_SELF']) == 'keranjang.php') echo 'class="active"'; ?>>Keranjang Pesanan</div>
        <div <?php if(basename($_SERVER['PHP_SELF']) == 'checkout.php') echo 'class="active"'; ?>>Checkout</div>
        <div <?php if(basename($_SERVER['PHP_SELF']) == 'pembayaran.php') echo 'class="active"'; ?>>Pembayaran</div>
        <div <?php if(basename($_SERVER['PHP_SELF']) == 'transaksi_selesai.php') echo 'class="active"'; ?>>Selesai</div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="section metode-pembayaran-container">
                    <h3>Metode Pembayaran</h3>
                    <form method="POST" action="pembayaran.php?id=<?php echo $emailuser; ?>">
                        <div class="mb-3">
                            <label for="bankTransfer" class="form-label">Bank Transfer</label>
                            <select class="form-select" id="bankTransfer" name="payment_method">
                                <option selected>Pilih Bank</option>
                                <option value="Mandiri">Bank Mandiri</option>
                                <option value="BCA">Bank BCA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="eWallet" class="form-label">E-Wallet</label>
                            <select class="form-select" id="eWallet" name="payment_method">
                                <option selected>Pilih E-Wallet</option>
                                <option value="Shopeepay">Shopeepay</option>
                                <option value="Gopay">GoPay</option>
                                <option value="Dana">Dana</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cardPayment" class="form-label">Card Payment</label>
                            <select class="form-select" id="cardPayment" name="payment_method">
                                <option selected>Pilih Kartu</option>
                                <option value="Paypal">Paypal</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section total-pembayaran-container">
                    <h3>Total Pembayaran</h3>
                    <div>
                        <p>Total Harga : Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                        <p>Biaya Admin G.N.A.ID : Rp. <?php echo number_format($service_fee, 0, ',', '.'); ?></p>
                        <p>Total Bayar : Rp. <?php echo number_format($total_amount, 0, ',', '.'); ?></p>
                        <p id="metodePembayaran">Metode Pembayaran : </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
            <button onclick="window.location.href='transaksi_selesai.php?id=<?php echo $emailuser; ?>'" style="margin-bottom: 10px;">Lakukan Pembayaran</button>
            <button onclick="window.location.href='checkout.php?id=<?php echo $emailuser; ?>'">Kembali</button>
        </div>
    </div>

    
        <?php require "footer.php" ?>

        <?php require "whatsapp-button.php" ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const bankTransfer = document.getElementById('bankTransfer');
    const eWallet = document.getElementById('eWallet');
    const cardPayment = document.getElementById('cardPayment');
    const metodePembayaran = document.getElementById('metodePembayaran');

        const paymentDetails = {
            "Mandiri": "1480749461 A.N Gina Athusalimah",
            "BCA": "1770006903873 A.N Gina Athusalimah",
            "Shopeepay": "+62 821-3041-0541 A.N Gina Athusalimah",
            "Gopay": "+62 821-3041-0541 A.N Gina Athusalimah",
            "Dana": "+62 821-3041-0541 A.N Gina Athusalimah",
            "Paypal": "paypal@example.com"
        };

        function resetOtherMethods(except) {
        if (except !== 'bankTransfer') {
            bankTransfer.selectedIndex = 0;
        }
        if (except !== 'eWallet') {
            eWallet.selectedIndex = 0;
        }
        if (except !== 'cardPayment') {
            cardPayment.selectedIndex = 0;
        }
    }

    function updatePaymentMethod() {
        let selectedMethod = '';
        if (bankTransfer.value !== 'Pilih Bank') {
            selectedMethod = bankTransfer.value;
        } else if (eWallet.value !== 'Pilih E-Wallet') {
            selectedMethod = eWallet.value;
        } else if (cardPayment.value !== 'Pilih Kartu') {
            selectedMethod = cardPayment.value;
        }
        metodePembayaran.textContent = `Metode Pembayaran : ${selectedMethod}`;

        if (selectedMethod) {
            const paymentInfo = paymentDetails[selectedMethod];
            fetch('pembayaran.php?id=<?php echo $emailuser; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `payment_method=${encodeURIComponent(selectedMethod)}&payment_info=${encodeURIComponent(paymentInfo)}`
            });
        }
    }

    bankTransfer.addEventListener('change', function() {
        resetOtherMethods('bankTransfer');
        updatePaymentMethod();
    });

    eWallet.addEventListener('change', function() {
        resetOtherMethods('eWallet');
        updatePaymentMethod();
    });

    cardPayment.addEventListener('change', function() {
        resetOtherMethods('cardPayment');
        updatePaymentMethod();
    });
});
</script>


</body>
</html>

