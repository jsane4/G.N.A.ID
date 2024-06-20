<?php
require 'db_connect.php';
require 'session.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$products = [];
if (!empty($searchQuery)) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE nama_produk LIKE ? OR deskripsi LIKE ?");
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = $product_id");
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$product_name = $product_price = $product_description = $product_image = '';
if (!empty($products)) {
    $product = $products[0];
    $product_id = $product['id_produk'];
    $product_name = $product['nama_produk'];
    $product_price = $product['harga'];
    $product_description = $product['deskripsi'];
    $product_image = $product['gambar'];
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Produk</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            .alert-success {
                color: #155724;
                background-color: #d4edda;
                border-color: #c3e6cb;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: .25rem;
            }

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #FFFFFF;
            }

            .container {
                position: relative;
                width: 80%;
                max-width: 1200px;
                background-color: white;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                display: flex;
                flex-direction: column;
                margin: auto;
                margin-top: 20px;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
                font-size: 18px;
                background-color: #f00;
                color: white;
                border: none;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .product-details {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                /* Align items to the top */
                margin-bottom: 20px;
                /* Add margin for spacing */
            }

            .product-info {
                width: auto;
                text-align: center;
                flex-grow: 1;
            }

            .product-info h2 {
                margin-top: 50px;
                /* Remove top margin */
            }

            .product-info p {
                margin: 10px 0;
                font-size: 18px;
                color: #333;
            }

            .product-description {
                margin-top: 20px;
                /* Add margin for spacing */
                font-size: 16px;
                line-height: 1.6;
                text-align: left;
                margin-left: 50px;
            }

            .product-photo {
                width: 100%;
                max-width: 400px;
                margin-right: 20px;
                /* Add margin for spacing */
            }

            .product-photo img {
                width: 100%;
                height: auto;
                object-fit: cover;
                border-radius: 8px;
            }

            .product-actions {
                display: flex;
                justify-content: flex-end;
                align-items: center;
            }

            .qty-add-to-cart {
                display: flex;
                align-items: center;
                margin-right: 20px;
            }

            .qty-add-to-cart input {
                width: 60px;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 5px;
                margin-right: 10px;
                font-size: 16px;
            }

            .qty-add-to-cart button {
                padding: 10px 20px;
                border: none;
                background-color: #000000;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            .qty-add-to-cart button:hover {
                background-color: #808080;
            }

            .social-icons {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1px;
                margin-right: 20px;
            }

            .social-icons a {
                font-size: 35px;
                margin-right: 10px;
                color: #333;
                transition: color 0.3s;
            }

            .social-icons a:hover {
                color: #007bff;
            }

            .bi-tiktok {
                color: #000;
            }

            .bi-facebook {
                color: #3b5998;
            }

            .bi-instagram {
                color: #e4405f;
            }

            .bi-tiktok:hover {
                color: #808080;
            }

            .bi-facebook:hover {
                color: #808080;
            }

            .bi-instagram:hover {
                color: #808080;
            }

            .wishlist-icon {
                font-size: 30px;
                color: #333;
                cursor: pointer;
                margin-left: 10px;
            }

            .wishlist-icon.active {
                color: red;
            }
        </style>
    </head>

    <body>
        <?php require "header.php" ?>
        <div class="container mt-4">
            <?php if (empty($products)): ?>
                <div class="alert alert-warning">
                    Produk tidak ditemukan.
                </div>
            <?php else: ?>
                <button class="close-btn" onclick="closeDetail()">X</button>
                <div class="product-details">
                    <div class="product-photo">
                        <img id="product-image" src="gambar/<?php echo htmlspecialchars($product_image); ?>"
                            alt="<?php echo htmlspecialchars($product_name); ?>" />
                    </div>
                    <div class="product-info">
                        <h2 id="product-name"><?php echo htmlspecialchars($product_name); ?></h2>
                        <div class="product-description" id="product-description">
                            <?php echo nl2br(htmlspecialchars($product_description)); ?>
                            <p id="product-price">Rp. <?php echo number_format($product_price, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="product-actions">
                    <div class="qty-add-to-cart">
                        <form method="post">
                            <input type="number" id="product-qty" value="1" min="1" name="qty">
                            <button type="submit" name="tambah_keranjang">Add to Cart</button>
                        </form>
                    </div>
                </div>
                <div class="text-center">
                    <?php
                    if (isset($_POST['tambah_keranjang'])) {
                        if ($_SESSION == false) {
                            ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                Harap login terlebih dahulu
                            </div>
                            <meta http-equiv="refresh" content="1;url=login.php" />
                            <?php
                        } else {
                            $qpesan = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_produk='$product_id'");
                            $count = mysqli_num_rows($qpesan);
                            if ($count > 0) {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                    pesanan sudah ada
                                </div>
                                <?php
                            } else {
                                if ($_POST['qty'] <= 0) {
                                    ?>
                                    <div class="alert alert-warning mt-3" role="alert">
                                        Tulis jumlah produk yang dipesan
                                    </div>
                                    <?php
                                } else {
                                    $iduser = $emailuser1['id_users'];
                                    $idproduk = $product['id_produk'];
                                    $qty = $_POST['qty'];

                                    $masuk = mysqli_query($conn, "INSERT INTO keranjang (id_users, id_produk, kuantitas) VALUES('$iduser', '$idproduk', '$qty')");
                                    if ($masuk) {
                                        ?>
                                        <div class="alert alert-primary mt-3" role="alert">
                                            Pesanan telah ditambahkan
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <div class="social-icons">
                    <a href="https://m.facebook.com/gnaid-100066774738487/"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.tiktok.com/@g.n.a.id"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.instagram.com/g.n.a.id/?hl=en"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        <?php endif; ?>
        </div>

        <?php require "whatsapp-button.php" ?>

        <script>
            function closeDetail() {
                window.history.back();
            }
        </script>
        <script>
            function toggleWishlist(productId) {
                var icon = document.getElementById('wishlist-icon');
                icon.classList.toggle('active');
                var isActive = icon.classList.contains('active');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'wishlist.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('product_id=' + productId + '&action=' + (isActive ? 'add' : 'remove'));
            }
        </script>
    </body>

</html>