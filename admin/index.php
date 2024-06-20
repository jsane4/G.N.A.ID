<?php
require "session.php";
require "koneksi.php";

$mysqli = new mysqli("localhost", "root", "", "gna_store");

$username = $_SESSION['username'];
$user = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
$userlogin = mysqli_fetch_assoc($user);

$query_produk = "SELECT id_produk, nama_produk, harga, gambar FROM produk";
$result_produk = mysqli_query($con, $query_produk);

$query_banners = "SELECT * FROM banners";
$result_banners = $mysqli->query($query_banners);
$banners = array();
while ($row = $result_banners->fetch_assoc()) {
    $banners[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G.N.A.ID Hijab Store - ADMIN</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
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
            
        .cart-nav {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            background-color: #fff;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .cart-nav div {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 20px;
        }

        .cart-nav div.active {
            background-color: #7285a5;
            color: #fff;
        }

        .cart-nav div:hover {
            color: #fff;
            background-color: #8e8e8e;
        }

        .link {
            text-decoration: none;
            color: black;
        }

        .bi-cart-fill,
        .bi-person-fill,
        .bi-list {
            color: black;
            font-size: 50px;
        }

        .icons i {
            margin-right: 10px;
        }

        .icon-menu {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .bi,
        a {
            color: black;
        }

        .icon-size {
            font-size: 30px;
        }

        .products-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
            overflow-y: auto;
            max-height: 900px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            height: 100%;
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
</head>
<body>
    <?php require "header.php"; ?>

    <div class="cart-nav">
        <a class="link" href="kategori.php">
            <div>Kelola Kategori</div>
        </a>
        <a class="link" href="produk.php">
            <div>Kelola Produk</div>
        </a>
        <a class="link" href="banner.php">
            <div>Kelola Banner</div>
        </a>
    </div>

    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($banners as $key => $banner) : ?>
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

    <div class="products-container">
        <div class="products">
            <?php
            if (mysqli_num_rows($result_produk) > 0) {
                while($row = mysqli_fetch_assoc($result_produk)) {
                    echo '<div class="product">';
                    echo '<img src="../gambar/' . $row["gambar"] . '" alt="' . $row["nama_produk"] . '">';
                    echo '<h3>' . $row["nama_produk"] . '</h3>';
                    echo '<p>Rp. ' . number_format($row["harga"], 0, ',', '.') . '</p>';
                    echo '</div>';
                }
            } else {
                echo "No products found.";
            }

            mysqli_close($con);
            ?>
        </div>
    </div>

    <?php require "footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
