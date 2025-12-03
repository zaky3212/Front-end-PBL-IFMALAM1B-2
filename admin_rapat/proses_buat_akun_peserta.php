<?php
session_start();
include '../koneksi.php';

// ambil data dari request
$participant_id = intval($_POST['participant_id']);
$email          = mysqli_real_escape_string($koneksi, $_POST['email']);
$username       = mysqli_real_escape_string($koneksi, $_POST['username']);
$password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Cek apakah peserta sudah punya akun
$cek_participant = mysqli_query($koneksi, "SELECT * FROM users WHERE participant_id='$participant_id'");
if(mysqli_num_rows($cek_participant) > 0){
    header("Location: tambah_akun.php?error=Akun peserta sudah ada");
    exit();
}

// Cek email unik
$cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0){
    header("Location: tambah_akun.php?error=Email sudah digunakan");
    exit();
}

// Cek username unik
$cek_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
if(mysqli_num_rows($cek_username) > 0){
    header("Location: tambah_akun.php?error=Username sudah digunakan");
    exit();
}

// Insert akun baru
$query = "INSERT INTO users(username, email, password, role, participant_id)
          VALUES('$username', '$email', '$password', 'user', '$participant_id')";

if(mysqli_query($koneksi, $query)){
    header("Location: tambah_akun.php?success=1");
} else {
    header("Location: tambah_akun.php?error=Gagal menyimpan ke database");
}
exit();
?>
