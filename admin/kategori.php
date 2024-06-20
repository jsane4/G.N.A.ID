<?php

session_start();
require "koneksi.php";
$mysqli = new mysqli("localhost", "root", "", "gna_store");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['add_category'])) {
    $category_name = $mysqli->real_escape_string($_POST['category_name']);
    $_SESSION['changes']['categories'][] = ['action' => 'add', 'name' => $category_name];
}

if (isset($_POST['save_changes'])) {
    if (isset($_SESSION['changes']['categories'])) {
        foreach ($_SESSION['changes']['categories'] as $category) {
            if ($category['action'] == 'add') {
                $mysqli->query("INSERT INTO kategori (nama_kategori) VALUES ('{$category['name']}')");
            }
        }
    }
    unset($_SESSION['changes']);
}

if (isset($_POST['cancel_changes'])) {
    unset($_SESSION['changes']);
}

if (isset($_GET['delete_category'])) {
    $category_id = $mysqli->real_escape_string($_GET['delete_category']);
    $mysqli->query("DELETE FROM kategori WHERE id_kategori='$category_id'");
}

if (isset($_GET['action']) && $_GET['action'] == 'get_categories') {
    $result = $mysqli->query("SELECT * FROM kategori");
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($categories);
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
            <div class="active">Kelola Kategori</div>
            <a class="link" href="produk.php">
                <div>Kelola Produk</div>
            </a>
            <a class="link" href="banner.php">
                <div>Kelola Banner</div>
            </a>
        </div>

        <div class="add-buttons">
           <button class="btn custom-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Tambahkan Kategori</button>
        </div>
        
        <div class="container">
            <div class="admin-content">
                <div class="container">
                    <div class="category-container">
                        <h3>Kategori</h3>
                        <div class="category-list">
                            <?php while ($category = $categories->fetch_assoc()) { ?>
                                <div class="category-item">
                                    <?php echo htmlspecialchars($category['nama_kategori']); ?>
                                    <a href="?delete_category=<?php echo $category['id_kategori']; ?>"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </div>
                            <?php } ?>

                            <!-- Menampilkan kategori yang belum disimpan -->
                            <?php if (isset($_SESSION['changes']['categories'])) {
                                foreach ($_SESSION['changes']['categories'] as $category) {
                                    if ($category['action'] == 'add') { ?>
                                        <div class="category-item">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                            <span class="badge bg-warning">Baru</span>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    </div>

                    <!-- Buttons to save or cancel changes -->
                    <?php if (isset($_SESSION['changes'])) { ?>
                        <div class="save-cancel-buttons">
                            <form method="post">
                                <button type="submit" name="save_changes" class="btn btn-success">Simpan Perubahan</button>
                                <button type="submit" name="cancel_changes" class="btn btn-danger">Batal</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
        
                <!-- Modal for adding category -->
                <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCategoryModalLabel">Tambahkan Kategori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Nama Kategori</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="add_category" class="btn btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="add-buttons">
    <button class="btn custom-btn" onclick="window.location.href = 'index.php';">Kembali</button>
</div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    </body>

</html>