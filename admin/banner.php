<?php

session_start();
require "koneksi.php";
$mysqli = new mysqli("localhost", "root", "", "gna_store");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle add banner
if (isset($_POST['add_banner'])) {
    // Mengunggah gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $upload_dir = '../gambar/'; // Tentukan direktori unggahan
        $image_path = $upload_dir . basename($image['name']);

        // Memindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $image_url = $image_path; // Gunakan path atau URL sesuai kebutuhan Anda
        } else {
            $image_url = ''; // Handle error if needed
        }
    }

    $_SESSION['changes']['banners'][] = [
        'action' => 'add',
        'image_url' => $image_url,
    ];
}

// Handle edit banner
if (isset($_POST['edit_banner'])) {
    $banner_id = $mysqli->real_escape_string($_POST['banner_id']);

    if (isset($_POST['add_banner'])) {
    // Mengunggah gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $upload_dir = '../gambar/'; // Tentukan direktori unggahan
        $image_path = $upload_dir . basename($image['name']);

        // Memindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $image_url = $image_path; // Gunakan path atau URL sesuai kebutuhan Anda
            $_SESSION['changes']['banners'][] = [
                'action' => 'add',
                'image_url' => $image_url,
            ];
        } else {
            $image_url = ''; // Handle error if needed
        }
    }
}

}

// Handle save changes
if (isset($_POST['save_changes'])) {
    if (isset($_SESSION['changes']['banners'])) {
        foreach ($_SESSION['changes']['banners'] as $banner) {
            if ($banner['action'] == 'add') {
                $mysqli->query("INSERT INTO banners (image) VALUES ('{$banner['image_url']}')");
            }
        }
    }
    unset($_SESSION['changes']);
}

// Handle save edit changes
if (isset($_POST['edit_changes'])) {
    if (isset($_SESSION['changes']['banners'])) {
        foreach ($_SESSION['changes']['banners'] as $banner) {
            if ($banner['action'] == 'edit') {
                $mysqli->query("UPDATE banners SET image='{$banner['image_url']}' WHERE id='{$banner['id']}'");
            }
        }
    }
    unset($_SESSION['changes']);
    echo '<meta http-equiv="refresh" content="0; url=banner.php" />';
}

// Handle cancel changes
if (isset($_POST['cancel_changes'])) {
    unset($_SESSION['changes']);
}

// Handle delete banner
if (isset($_GET['delete_banner'])) {
    $banner_id = $mysqli->real_escape_string($_GET['delete_banner']);
    $mysqli->query("DELETE FROM banners WHERE id='$banner_id'");
}

// API to fetch banners
if (isset($_GET['action']) && $_GET['action'] == 'get_banners') {
    $result = $mysqli->query("SELECT * FROM banners");
    $banners = array();
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($banners);
    exit;
}

// Fetch banners
$banners = $mysqli->query("SELECT * FROM banners");
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

            .cart-actions button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #0e4c92;
            color: white;
            border-radius: 5px;
        }
        .cart-actions button:hover {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #e9ecef;
            color: black;
            border-radius: 5px;
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
        <div class="active">Kelola Banner</div>
        </a>
    </div>
    <div class="add-buttons">
        <button class="btn custom-btn" data-bs-toggle="modal" data-bs-target="#addBannerModal">Tambahkan Banner</button>
    </div>
    <div class="admin-content">
        <div class="container">
            <div class="banners-container">
                <div class="col-12 text-center">
                <h3 class="pb-3">BANNER</h3>
            </div>
                <div class="row">
                    <?php while ($data = mysqli_fetch_array($banners)) { ?>
                    <div class="col-6">
                        <div class="banner-item">
                            <img src="<?php echo ($data['image']); ?>" class="d-block w-100">
                            <div class="banner-buttons">
                                <div class="p-1">
                                    <a class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editBannerModal<?php echo $data['id']; ?>">Edit</a>
                                </div>
                                <div class="p-1">
                                    <a href="?delete_banner=<?php echo $data['id']; ?>"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for edit banner -->
                    <div class="modal fade" id="editBannerModal<?php echo $data['id']; ?>" tabindex="-1"
                        aria-labelledby="editBannerModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBannerModalLabel">Edit Banner</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <input type="hidden" class="form-control" id="banner_id" name="banner_id"
                                                value="<?php echo $data['id']; ?>" required>
                                            <div class="text-center">
                                                <label for="image" class="form-label">Gambar Banner</label>
                                                <center><img src="<?php echo $data['image']; ?>"
                                                        class="d-block w-100 pb-3"></center>
                                            </div>
                                            <input type="file" class="form-control" id="image" name="image"
                                                value="<?php echo $data['image']; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_banner" class="btn btn-primary">Ubah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Menampilkan banner yang belum disimpan -->
                    <?php if (isset($_SESSION['changes']['banners'])) {
                        foreach ($_SESSION['changes']['banners'] as $banner) {
                            if ($banner['action'] == 'add') { ?>
                    <div class="col-6">
                        <div class="banner-item">
                            <div class="text-center">
                                <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" class="d-block w-100 pb-3">
                            </div>
                            <div class="banner-buttons">
                                <span class="badge bg-warning">Baru</span>
                            </div>
                        </div>
                    </div>
                    <?php }
                        }
                    } ?>
                    <!-- Menampilkan banner edit yang belum disimpan -->
                    <?php if (isset($_SESSION['changes']['banners'])) {
                        foreach ($_SESSION['changes']['banners'] as $banner) {
                            if ($banner['action'] == 'edit') { ?>
                    <div class="col-6">
                        <div class="banner-item">
                            <div class="text-center">
                                <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" class="d-block w-100 pb-3">
                            </div>
                            <div class="banner-buttons">
                                <span class="badge bg-warning">Diedit</span>
                            </div>
                        </div>
                    </div>
                    <?php }
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Actions -->
            <div class="col-12 d-flex justify-content-center mt-4 mb-4">
                <form method="post">
                    <button type="submit" name="save_changes" class="btn btn-primary btn-success">Simpan Semua Perubahan</button>
                    <button type="submit" name="edit_changes" class="btn btn-primary btn-success">Simpan Semua Edit</button>
                    <button type="submit" name="cancel_changes" class="btn btn-secondary">Batalkan Semua Perubahan</button>
                    <div class="add-buttons">
                    <button class="btn custom-btn" onclick="window.location.href = 'index.php';">Kembali</button>
                    </div>
                </form>
            </div>

    <!-- Add Banner Modal -->
    <div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">Tambahkan Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Banner</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_banner" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</html>