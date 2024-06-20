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
</style>
<header class="d-flex justify-content-between align-items-center ps-auto pe-auto">
   <div class="logo">
        <a href="index.php">
            <img src="<?php echo isset($_SESSION['logo_path']) ? $_SESSION['logo_path'] : '../gambar/logojadi.jpg'; ?>" alt="Logo G.N.A.ID">
        </a>
    </div>
    <div class="icons">
        <div class="icon-menu dropdown">
            <i class="bi bi-person-circle icon-size" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="akun_admin.php"><?php echo $userlogin['nama_lengkap'] ?></a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</header>