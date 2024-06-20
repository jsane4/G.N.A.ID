<?php
include 'db_connect.php';
include 'session.php';

$emailuser = $_GET['id'];

$pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_users='$emailuser'");

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Keranjang Pesanan - G.N.A Fashion Store</title>
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
                padding: 70px;
            }

            .cart-details table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .cart-details table,
            th,
            td {
                border: 1px solid #ddd;
            }

            .cart-details th,
            .cart-details td {
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
                /* Adding margin top to create space between header and container */
            }
        </style>
    </head>

    <body>
        <?php require "header.php" ?>

        <div class="cart-nav">
            <div <?php if (basename($_SERVER['PHP_SELF']) == 'keranjang.php')
                echo 'class="active"'; ?>>Keranjang Pesanan
            </div>
            <div <?php if (basename($_SERVER['PHP_SELF']) == 'checkout.php')
                echo 'class="active"'; ?>>Checkout</div>
            <div <?php if (basename($_SERVER['PHP_SELF']) == 'pembayaran.php')
                echo 'class="active"'; ?>>Pembayaran</div>
            <div <?php if (basename($_SERVER['PHP_SELF']) == 'selesai.php')
                echo 'class="active"'; ?>>Selesai</div>
        </div>

        <div class="container">
            <div class="cart-details">
                <h2>Detail Pesanan</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Detail Produk</th>
                            <th>Kuantitas</th>
                            <th>Total Harga</th>
                            <th>Sub Total Harga</th>
                            <th>Edit Kuantitas</th>
                            <th>Hapus Produk</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    $total = 0;
                    while ($qpesanan = mysqli_fetch_array($pesanan)) {
                        $produk = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$qpesanan[id_produk]'");
                        while ($qproduk = mysqli_fetch_array($produk)) {
                            $jumlah = $qpesanan['kuantitas'] * $qproduk['harga'];
                            $total += $jumlah;
                            ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $qproduk['nama_produk'] ?></td>
                                <td><?php echo $qpesanan['kuantitas'] ?></td>
                                <td><?php echo 'Rp.' . number_format(($jumlah), 0, ',', '.') ?></td>
                                <td><?php echo 'Rp.' . number_format($qproduk['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <form class="form-control-sm mt-1" method='post' style='display:inline-block;'>
                                        <input type='hidden' name='id_order' value='<?php echo $qpesanan['id_keranjang'] ?>'>
                                        <input class="text-center w-75" type='number' name='jumlah_baru'
                                            value='<?php echo $qpesanan['kuantitas'] ?>' min='1'>
                                        <button type='submit' name='edit' class='btn btn-black mt-1'>Edit</button>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit'])) {
                                        $id = $_POST['id_order'];
                                        $jmlbaru = $_POST['jumlah_baru'];
                                        $edit = mysqli_query($conn, "UPDATE keranjang SET kuantitas='$jmlbaru' WHERE id_keranjang='$id' ");
                                        if ($edit) {
                                            ?>
                                            <script>
                                                window.location = history.go(-1);
                                            </script>
                                            <?php
                                        } else {
                                            echo "Pesanan Gagal diubah" . mysqli_error($con);
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form method='' style='display:inline-block;'>
                                        <button class="btn btn-danger" type="button"
                                            data-bs-target="#hapusproduk<?php echo $qpesanan['id_keranjang'] ?>"
                                            data-bs-toggle="modal">Hapus</button>
                                    </form>
                                </td>
                                <!-- Modal Hapus-->
                                <div class="modal fade" id="hapusproduk<?php echo $qpesanan['id_keranjang'] ?>" tabindex="-1"
                                    aria-labelledby="hapusProdukLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="hapusProdukLabel">Hapus Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Yakin menghapus produk ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">Tidak</button>
                                                <form action="" method="POST">
                                                    <input type='hidden' name='id_order'
                                                        value='<?php echo $qpesanan['id_keranjang'] ?>'>
                                                    <input type="submit" class="btn btn-danger" name="hapus_produk"
                                                        id="hapus_produk" value="Ya">
                                                    <?php
                                                    if (isset($_POST['hapus_produk'])) {
                                                        $id = $_POST['id_order'];
                                                        $hapus = mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id' ");
                                                        if ($hapus) {
                                                            ?>
                                                            <div class="alert alert-primary mt-3" role="alert">
                                                                Data Berhasil Dihapus!
                                                            </div>
                                                            <script>
                                                                window.location = history.go(-1);
                                                            </script>
                                                            content="0;
                                                            url=keranjang.php?id=<?php echo $qpesanan['id_users'] ?>" />
                                                            <?php
                                                        } else {
                                                            echo mysqli_error($conn);
                                                        }
                                                    }
                        }
                    }
                    ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-center">TOTAL</td>
                            <td><?php echo 'Rp.' . number_format(($total), 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <div class="cart-actions" style="flex-direction: column; align-items: flex-end;">
                <button onclick="window.location.href='produk.php'" style="margin-bottom: 10px;">Tambah Pesanan</button>
                <button onclick="window.location.href='checkout.php?id=<?php echo $emailuser1['id_users']; ?>'"
                    style="margin-bottom: 10px;">Checkout</button>
                <button
                    onclick="window.location.href='index.php?id=<?php echo $emailuser1['id_users']; ?>'">Kembali</button>
            </div>
        </div>

        <?php require "footer.php"; ?>

        <?php
        require "whatsapp-button.php"
            ?>

        <script src="js/script.js"></script>
    </body>

</html>