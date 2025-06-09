<?php
$con = mysqli_connect("localhost", "root", "", "project_uas");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();


}
// $host = "sql113.infinityfree.com";  // Ganti XXX dengan server Anda
// $user = "if0_39191523";           // Username database Anda di InfinityFree
// $pass = "akuunpad123";          // Password yang Anda buat di InfinityFree
// $db   = "if0_39191523_project_uas"; // Nama database lengkap di InfinityFree

// $con = mysqli_connect($host, $user, $pass, $db);

// if (mysqli_connect_errno()) {
//     echo "Failed to connect to MySQL: " . mysqli_connect_error();
//     exit();
// }
?>
