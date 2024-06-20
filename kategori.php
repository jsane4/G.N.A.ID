<?php
require 'db_connect.php';
require "session.php";

$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if (!empty($kategori_id)) {
    $query = "SELECT id_produk, nama_produk, harga, gambar FROM produk WHERE id_kategori = '$kategori_id'";
} else {
    $query = "SELECT id_produk, nama_produk, harga, gambar FROM produk";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kategori Produk</title>
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

            .header {
                text-align: center;
                margin-bottom: 20px;
                margin-top: 20px;
            }

            .categories {
                display: flex;
                justify-content: flex-start;
                padding: 30px;
                background-color: #FFFFFF;
                margin-bottom: flex;
            }

            .categories .sidebar div {
                padding: 10px 20px;
                cursor: pointer;
                background-color: #f0f0f0;
                border: 1px solid #ccc;
                border-radius: 20px;
                transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
                margin-bottom: 10px;
            }

            .categories .sidebar div a {
                text-decoration: none;
                color: #000;
                display: block;
                width: 100%;
                height: flex;
                background-color: inherit;
            }

            .categories .sidebar div:hover {
                background-color: #f0f0f0;
                transform: scale(1.05);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .categories div:hover {
                background-color: #ffffff;
            }

            .container {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
                margin: 20px;
            }

            .sidebar {
                width: 200px;
                margin-right: 100px;
            }

            .sidebar button {
                display: block;
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #000;
                background-color: #f0f0f0;
                cursor: pointer;
            }

            .products {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                margin: 0 auto;
                max-width: 1200px;
            }

            .product {
                background-color: #fff;
                border: none;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                text-align: center;
                width: 200px;
                padding: 20px;
                transition: transform 0.2s;
                position: relative;
            }

            .product img {
                width: 100%;
                height: auto;
                border-radius: 10px;
                transition: opacity 0.3s;
            }

            .product h3 {
                font-size: 1.1em;
                margin: 10px 0;
            }

            .product p {
                font-size: 0.9em;
                margin: 5px 0;
                color: #666;
            }

            .product a {
                display: block;
                text-align: center;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: #fff;
                padding: 10px 20px;
                border-radius: 20px;
                text-decoration: none;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .product:hover a {
                opacity: 0;
            }

            .product:hover {
                transform: translateY(-10px);
            }
        </style>
    </head>

    <body>
        <?php require "header.php" ?>

        <div class="categories">
            <div class="sidebar">
                <div><a href="kategori.php">Semua Produk</a></div>
                <div><a href="kategori.php?kategori=4">Best Seller Produk</a></div>
                <div><a href="kategori.php?kategori=2">Dress Muslim</a></div>
                <div><a href="kategori.php?kategori=1">Hijab</a></div>
                <div><a href="kategori.php?kategori=5">Mukena</a></div>
                <div><a href="kategori.php?kategori=3">Inner</a></div>
            </div>

            <div class="products">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($product = mysqli_fetch_assoc($result)) {
                        echo '<div class="product">';
                        echo '<img src="gambar/' . $product["gambar"] . '" alt="' . $product["nama_produk"] . '">';
                        echo '<h3>' . $product["nama_produk"] . '</h3>';
                        echo '<p>Rp. ' . number_format($product["harga"], 0, ',', '.') . '</p>';
                        echo '<a href="detail_produk.php?id=' . $product["id_produk"] . '"></a>';
                        echo '</div>';
                    }
                } else {
                    echo "No products found.";
                }

                mysqli_close($conn);
                ?>
            </div>
        </div>

        <?php require "footer.php" ?>
        <?php require "whatsapp-button.php" ?>
    </body>

</html>