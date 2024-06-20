<?php
session_start();
if ($_SESSION == false) {

} else {
    $emailuser = $_SESSION['email'];
    $users = mysqli_query($conn, "SELECT * FROM users WHERE email='$emailuser' ");
    $emailuser1 = mysqli_fetch_array($users);

}