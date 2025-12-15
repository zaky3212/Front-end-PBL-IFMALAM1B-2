<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: ../login.php");
    exit();
}

$participant_id=intval($_POST['participant_id']);
$email=mysqli_real_escape_string($koneksi,$_POST['email']);
$username=mysqli_real_escape_string($koneksi,$_POST['username']);
$password=$_POST['password'];

if(!$participant_id || !$email || !$username || !$password){
    header("Location: tambah_akun.php?error=Semua field wajib diisi");
    exit();
}

$hash=password_hash($password,PASSWORD_DEFAULT);

$cek=mysqli_query($koneksi,"SELECT id FROM users WHERE email='$email' OR username='$username'");
if(mysqli_num_rows($cek)>0){
    header("Location: tambah_akun.php?error=Email atau username sudah digunakan");
    exit();
}

mysqli_query($koneksi,"
INSERT INTO users (username,email,password,role,participant_id)
VALUES ('$username','$email','$hash','user','$participant_id')
");

header("Location: tambah_akun.php?success=1");
exit();
