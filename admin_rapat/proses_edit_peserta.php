<?php
session_start();
include '../koneksi.php';

$id          = intval($_POST['id']);
$name        = mysqli_real_escape_string($koneksi, $_POST['name']);
$email       = mysqli_real_escape_string($koneksi, $_POST['email']);
$department  = mysqli_real_escape_string($koneksi, $_POST['department']);
$position    = mysqli_real_escape_string($koneksi, $_POST['position']);
$phone       = mysqli_real_escape_string($koneksi, $_POST['phone']);

$sql = "UPDATE participant SET 
        name='$name',
        email='$email',
        department='$department',
        position='$position',
        phone='$phone'
        WHERE id=$id";

if(mysqli_query($koneksi, $sql)){
    header("Location: peserta_admin.php?success=2");
    exit();
} else {
    header("Location: peserta_admin.php?error=2");
    exit();
}
?>
