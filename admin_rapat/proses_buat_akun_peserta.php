<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: ../login.php");
    exit();
}

$participant_id = intval($_POST['participant_id']);
$email    = mysqli_real_escape_string($koneksi,$_POST['email']);
$username = mysqli_real_escape_string($koneksi,$_POST['username']);
$password = $_POST['password'];

if(!$participant_id || !$email || !$username || !$password){
    header("Location: tambah_akun.php?error=Semua field wajib diisi");
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

/* 🔍 CEK APAKAH USER SUDAH ADA */
$cek = mysqli_query($koneksi, "SELECT id, password FROM users WHERE email='$email'");

if(mysqli_num_rows($cek) > 0){

    $user = mysqli_fetch_assoc($cek);

    /* ✅ JIKA USER ADA & PASSWORD MASIH NULL → UPDATE */
    if($user['password'] === NULL){
        mysqli_query($koneksi, "
            UPDATE users SET
                username='$username',
                password='$hash'
            WHERE email='$email'
        ");
        header("Location: tambah_akun.php?success=1");
        exit();
    }else{
        /* ❌ SUDAH ADA AKUN AKTIF */
        header("Location: tambah_akun.php?error=Akun sudah aktif");
        exit();
    }
}

/* ✅ JIKA USER BELUM ADA → INSERT BARU */
mysqli_query($koneksi,"
INSERT INTO users (username,email,password,role,participant_id)
VALUES ('$username','$email','$hash','user','$participant_id')
");

header("Location: tambah_akun.php?success=1");
exit();
