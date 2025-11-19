<?php
// koneksi.php
$host = "localhost";
$username = "root";
$password = "";
$database = "meeting_management";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else 
?>