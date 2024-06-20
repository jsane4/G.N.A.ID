<?php
include 'db_connect.php';

$query = "SELECT id_produk, nama_produk, harga, gambar FROM produk";
$result = mysqli_query($conn, $query);
?>

<style>
    .products-container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 1200px;
        overflow-y: auto;
        /* Tambahkan ini untuk memungkinkan scrolling vertikal */
        max-height: 900px;
        /* Sesuaikan dengan tinggi banner */
    }

    .products {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        height: 100%;
        /* Sesuaikan dengan tinggi container */
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
        /* For absolute positioning of the "View Details" link */
    }

    .product img {
        width: 100%;
        height: auto;
        border-radius: 10px;
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
        opacity: 1;
    }

    .product:hover {
        transform: translateY(-10px);
    }
</style>

<div class="products-container">
    <div class="products">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($product = mysqli_fetch_assoc($result)) {
                echo '<div class="product">';
                echo '<img src="gambar/' . $product["gambar"] . '" alt="' . $product["nama_produk"] . '">';
                echo '<h3>' . $product["nama_produk"] . '</h3>';
                echo '<p>Rp. ' . number_format($product["harga"], 0, ',', '.') . '</p>';
                echo '<a href="detail_produk.php?id=' . $product["id_produk"] . '">View Details</a>';
                echo '</div>';
            }
        } else {
            echo "No products found.";
        }

        mysqli_close($conn);
        ?>
    </div>
</div>