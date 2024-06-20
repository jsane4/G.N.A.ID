<?php
require "db_connect.php";
require "session.php";

$mysqli = new mysqli("localhost", "root", "", "gna_store");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM banners");
$banners = [];
while ($product = $result->fetch_assoc()) {
    $product['image'] = 'gambar/' . $product['image'];
    $banners[] = $product;
}
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
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #FFFFFF;
            }

            .categories {
                display: flex;
                justify-content: space-around;
                padding: 30px;
                background-color: #FFFFFF;
                border-bottom: 1px solid #ddd;
                margin-bottom: 30px;
            }

            .categories div {
                padding: 10px 20px;
                cursor: pointer;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 20px;
                transition: background-color 0.3s;
            }

            .categories div a {
                text-decoration: none;
                color: #000;
                display: block;
                width: 100%;
                height: 100%;
                background-color: inherit;
            }

            .categories div:hover {
                background-color: #808080;/
            }

            .categories div a:hover {
                color: #fff;
                background-color: inherit;
            }

            .carousel-item img {
                max-height: 900px;
                object-fit: cover;
                padding: 40px;
                text-align: center;
                background-color: #FFFFFF;
                margin: 10px;
                width: 100%;
                box-sizing: border-box;
            }
        </style>
    </head>

    <body>
        <?php
        require "header.php"
            ?>

        <div class="categories">
            <div><a href="kategori.php">Kategori Produk</a></div>
            <div><a href="kategori.php?kategori=4">Best Seller Produk</a></div>
        </div>

        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($banners as $key => $banner): ?>
                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo $banner['image']; ?>" alt="Banner Image <?php echo $key + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <?php
        require "produk.php"
            ?>

        <?php
        require "footer.php"
            ?>

        <?php
        require "whatsapp-button.php"
            ?>
    </body>

</html>