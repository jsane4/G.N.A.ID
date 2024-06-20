<?php

require "db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>G.N.A.ID Hijab Store</title>
    </head>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 40px;
            background-color: #FFFFFF;
            border-bottom: 1px solid #ddd;
        }

        header .logo img {
            width: 282px;
            height: 118px;
            border-radius: 30px;
        }

        header .search-bar {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        .search-bar {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .search-bar form {
            display: flex;
            width: 100%;
            max-width: 600px;
        }

        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            outline: none;
        }

        .search-bar button[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #000000;
            color: white;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            cursor: pointer;
        }

        .search-bar button[type="submit"]:hover {
            background-color: #808080;
        }

        header .icons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        header .icons a {
            margin-left: 10px;
            position: relative;
        }

        header .icons a:not(:first-child)::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 0;
            bottom: 0;
            width: 1px;
            background-color: #ccc;
        }
    </style>

    <header class="d-flex justify-content-between align-items-center ps-auto pe-auto">
        <div class="logo">
            <a href="index.php">
                <img src="<?php echo isset($_SESSION['logo_path']) ? $_SESSION['logo_path'] : 'gambar/logojadi.jpg'; ?>"
                    alt="Logo G.N.A.ID">
            </a>
        </div>
        <div class="search-bar">
            <form class="d-flex" id="searchForm" method="GET" action="detail_produk.php">
                <input class="form-control me-2" type="search" id="searchInput" name="query"
                    placeholder="Assalamu'alaikum ukhti, mau cari produk apa nih?" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
        <?php

        if ($_SESSION == false) {
            ?>
            <div class="icons">
                <div class="icon-menu dropdown">
                    <i class="bi bi-person-fill" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                    </ul>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="icons">
                <div class="icon-menu dropdown">
                    <i class="bi bi-person-fill" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item"
                                href="akun_user.php?id=<?php echo $emailuser1['id_users']; ?>"><?php echo $emailuser1['nama_lengkap'] ?></a>
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="icons">
                    <a href="keranjang.php?id=<?php echo $emailuser1['id_users']; ?>"><i class="bi bi-cart-fill"></i></a>
                </div>
            </div>
        <?php } ?>
    </header>