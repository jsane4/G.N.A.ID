<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "gna_store";
$con = mysqli_connect($host, $user, $pass, $db);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
