<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    exit();
}

$id=intval($_GET['id']);
mysqli_query($koneksi,"DELETE FROM users WHERE id='$id'");
header("Location: tambah_akun.php");
exit();
