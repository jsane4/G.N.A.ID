<?php

session_start();
require "koneksi.php";
$mysqli = new mysqli("localhost", "root", "", "gna_store");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['add_product'])) {
    $product_name = $mysqli->real_escape_string($_POST['product_name']);
    $category_id = $mysqli->real_escape_string($_POST['category_id']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $price = $mysqli->real_escape_string($_POST['price']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $upload_dir = '../gambar/'; 
        $image_path = $upload_dir . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $image_url = $image_path; 
        } else {
            $image_url = ''; 
        }
    }

    $_SESSION['changes']['products'][] = [
        'action' => 'add',
        'name' => $product_name,
        'category_id' => $category_id,
        'image_url' => $image_url,
        'description' => $description,
        'price' => $price,
    ];
}

if (isset($_POST['edit_product'])) {
    $product_id = $mysqli->real_escape_string($_POST['product_id']);
    $product_name = $mysqli->real_escape_string($_POST['product_name']);
    $category_id = $mysqli->real_escape_string($_POST['category_id']);
    $description = $mysqli->real_escape_string($_POST['description']);
    $price = $mysqli->real_escape_string($_POST['price']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $upload_dir = '../gambar/'; 
        $image_path = $upload_dir . basename($image['name']);
        if (basename($image['name'] != '')) {
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                $image_url = $image_path; 
            } else {
                $image_url = ''; 
            }
        }
    }

    $_SESSION['changess']['products'][] = [
        'action' => 'edit',
        'id' => $product_id,
        'name' => $product_name,
        'category_id' => $category_id,
        'image_url' => $image_url,
        'description' => $description,
        'price' => $price,
    ];
}

if (isset($_POST['save_changes'])) {
    if (isset($_SESSION['changes']['products'])) {
        foreach ($_SESSION['changes']['products'] as $product) {
            if ($product['action'] == 'add') {
                $mysqli->query("INSERT INTO produk (nama_produk, gambar, deskripsi, harga, id_kategori) VALUES ('{$product['name']}', '{$product['image_url']}', '{$product['description']}', '{$product['price']}', '{$product['category_id']}')");
            }
        }
    }
    unset($_SESSION['changes']);
}

if (isset($_POST['edit_changes'])) {
    if (isset($_SESSION['changess']['products'])) {
        foreach ($_SESSION['changess']['products'] as $product) {
            if ($product['action'] == 'edit') {
                $mysqli->query("UPDATE produk SET nama_produk='{$product['name']}', gambar='{$product['image_url']}', deskripsi='{$product['description']}', harga='{$product['price']}', id_kategori='{$product['category_id']}' WHERE id_produk='{$product['id']}'");
            }
        }
    }
    unset($_SESSION['changess']);
    echo '<meta http-equiv="refresh" content="0; url=produk.php" />';
}

if (isset($_POST['cancel_changes'])) {
    unset($_SESSION['changes']);
}

if (isset($_GET['delete_product'])) {
    $product_id = $mysqli->real_escape_string($_GET['delete_product']);
    $mysqli->query("DELETE FROM produk WHERE id_produk='$product_id'");
}

if (isset($_GET['action']) && $_GET['action'] == 'get_products') {
    $result = $mysqli->query("SELECT * FROM produk");
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($products);
    exit;
}

$categories = $mysqli->query("SELECT * FROM kategori");
$products = $mysqli->query("SELECT * FROM produk");
$username = $_SESSION['username'];
$user = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
$userlogin = mysqli_fetch_assoc($user);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>G.N.A.ID Hijab Store - ADMIN</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
            rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #FFFFFF;
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
            }

            .admin-container {
                max-width: 1200px;
                margin: auto;
                padding: 20px;
            }

            .admin-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
            }

            .admin-content {
                display: flex;
                justify-content: space-between;
                margin: 20px 0;
                gap: 2%;
            }

            .categories,
            .products {
                width: 48%;
            }

            .category-container,
            .products-container {
                width: 100%;
            }

            .category-container h3,
            .products-container h3 {
                text-align: center;
            }

            .category-list {
                display: flex;
                flex-wrap: wrap;
                gap: 1%;
            }

            .category-item {
                flex: 1 1 calc(33.33% - 1%);
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
            }

            .product-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
            }

            .add-form {
                display: flex;
                margin-top: 20px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .add-form input,
            .add-form select {
                flex: 1 1 100%;
                margin-right: 0;
                padding: 5px;
            }

            .add-form button {
                padding: 5px 5px;
                width: 100%;
            }

            .product-buttons {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }

            .container {
                width: 100%;
                padding: 20px;
                border: 1px solid #ddd;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background-color: #fff;
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

            .add-buttons {
                display: flex;
                justify-content: center;
                margin-top: 10px;
                background-color: #fff;
                padding: 10px;
            }

            .add-buttons button {
                margin: 0 5px;
            }

            .custom-btn {
              background-color: #7285a5;
              border-color: #7285a5;
               color: white;
          }
            .custom-btn:hover {
                background-color: #8e8e8e;
                border-color: #8e8e8e;
                color: white;
            }
        </style>
    </head>

    <body>
        <?php require "header.php"; ?>

        <div class="cart-nav">
            <a class="link" href="kategori.php">
                <div>Kelola Kategori
                </div>
            </a>
            <div class="active">Kelola Produk</div>
            <a class="link" href="banner.php">
                <div>Kelola Banner</div>
            </a>
        </div>
        <div class="add-buttons">
            <button class="btn custom-btn" data-bs-toggle="modal" data-bs-target="#addProductModal">Tambahkan Produk</button>
        </div>
        <div class="admin-content">
            <div class="container">
                <div class="products-container">
                    <h3 class="pb-3">PRODUK</h3>
                    <div class="row">
                        <?php
                        while ($data = mysqli_fetch_array($products)) {
                            ?>
                            <div class="col-6">
                                <div class="product-item">
                                    <img src="<?php echo ($data['gambar']); ?>" class="d-block w-25">
                                    <div>
                                        <h4><?php echo ($data['nama_produk']); ?></h4>
                                        <p><?php echo ($data['deskripsi']); ?></p>
                                        <p>Harga : Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></p>
                                        <p>Kategori : <?php echo $data['id_kategori']; ?></p>
                                    </div>
                                    <div class="product-buttons">
                                        <div class="p-1">
                                            <a class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editProductModal<?php echo $data['id_produk']; ?>">Edit</a>
                                        </div>
                                        <div class="p-1">
                                            <a href="?delete_product=<?php echo $data['id_produk']; ?>"
                                                class="btn btn-danger btn-sm">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal for edit product -->
                            <div class="modal fade" id="editProductModal<?php echo $data['id_produk']; ?>" tabindex="-1"
                                aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel">Edit
                                                Produk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="product_name" class="form-label">Nama
                                                        Produk</label>
                                                    <input type="hidden" class="form-control" id="product_id"
                                                        name="product_id" value="<?php echo $data['id_produk']; ?>"
                                                        required>
                                                    <input type="text" class="form-control" id="product_name"
                                                        name="product_name" value="<?php echo $data['nama_produk']; ?>"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="text-center">
                                                        <label for="image" class="form-label">Gambar
                                                            Produk</label>
                                                        <center><img src="<?php echo $data['gambar']; ?>"
                                                                class="d-block w-25 pb-3"></center>
                                                    </div>
                                                    <input type="file" class="form-control" id="image" name="image"
                                                        value="<?php echo $data['gambar']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Kategori</label>
                                                    <select class="form-control" id="category_id" name="category_id"
                                                        required>
                                                        <option value="<?php echo $data['id_kategori']; ?>">
                                                            <?php echo $data['id_kategori']; ?>
                                                        </option>
                                                        <?php
                                                        $result = $mysqli->query("SELECT * FROM kategori");
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Deskripsi</label>
                                                    <input class="form-control" id="description" name="description"
                                                        value="<?php echo $data['deskripsi']; ?>" required></input>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Harga</label>
                                                    <input type="number" class="form-control" id="price" name="price"
                                                        value="<?php echo $data['harga']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="edit_product"
                                                    class="btn btn-primary">Ubah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Menampilkan produk yang belum disimpan -->
                        <?php if (isset($_SESSION['changes']['products'])) {
                            foreach ($_SESSION['changes']['products'] as $product) {
                                if ($product['action'] == 'add') { ?>
                                    <div class="col-6">
                                        <div class="product-item">
                                            <div>
                                                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                                <p>Harga: Rp. <?php echo htmlspecialchars($product['price']); ?></p>
                                                <p>Kategori : <?php echo htmlspecialchars($product['category_id']); ?></p>
                                            </div>
                                            <div class="product-buttons">
                                                <span class="badge bg-warning">Baru</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } ?>
                        <!-- Buttons to save or cancel changes -->
                        <?php if (isset($_SESSION['changes'])) { ?>
                            <div class="save-cancel-buttons">
                                <form method="post">
                                    <button type="submit" name="save_changes" class="btn btn-success">Simpan Perubahan</button>
                                    <button type="submit" name="cancel_changes" class="btn btn-danger">Batal</button>
                                </form>
                            </div>
                        <?php } ?>
                        <!-- Menampilkan produk EDIT belum disimpan -->
                        <?php if (isset($_SESSION['changess']['products'])) {
                            foreach ($_SESSION['changess']['products'] as $product) {
                                if ($product['action'] == 'edit') { ?>
                                    <div class="col-6">
                                        <div class="product-item">
                                            <div>
                                                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                                <p>Harga: Rp<?php echo htmlspecialchars($product['price']); ?></p>
                                                <p>Kategori : <?php echo htmlspecialchars($product['category_id']); ?></p>
                                            </div>
                                            <div class="product-buttons">
                                                <span class="badge bg-warning">Baru</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } ?>
                        <!-- Buttons to save or cancel changes -->
                        <?php if (isset($_SESSION['changess'])) { ?>
                            <div class="save-cancel-buttons">
                                <form method="post">
                                    <button type="submit" name="edit_changes" class="btn btn-success">Simpan Perubahan</button>
                                    <button type="submit" name="cancel_changes" class="btn btn-danger">Batal</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="add-buttons">
        <button class="btn custom-btn" onclick="window.location.href = 'index.php';">Kembali</button>
        </div>

        <!-- Modal for adding product -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Tambahkan Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <?php
                                    $result = $mysqli->query("SELECT * FROM kategori");
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_product" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    </body>

</html>