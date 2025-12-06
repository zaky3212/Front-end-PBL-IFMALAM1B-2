<?php
session_start();
include '../koneksi.php';

// Pastikan hanya admin yang bisa akses
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// Ambil data dari request
$participant_id = intval($_POST['participant_id'] ?? 0);
$email          = mysqli_real_escape_string($koneksi, trim($_POST['email'] ?? ''));
$username       = mysqli_real_escape_string($koneksi, trim($_POST['username'] ?? ''));
$password_raw   = trim($_POST['password'] ?? '');

if(empty($participant_id) || empty($email) || empty($username) || empty($password_raw)){
    header("Location: tambah_akun.php?error=Semua field wajib diisi");
    exit();
}

$password = password_hash($password_raw, PASSWORD_DEFAULT);

// Validasi peserta
$cek_participant = mysqli_query($koneksi, "SELECT id FROM participant WHERE id='$participant_id'");
if(mysqli_num_rows($cek_participant) == 0){
    header("Location: tambah_akun.php?error=Peserta tidak valid");
    exit();
}

// Cek email unik
$cek_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0){
    header("Location: tambah_akun.php?error=Email sudah digunakan");
    exit();
}

// Cek username unik
$cek_username = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'");
if(mysqli_num_rows($cek_username) > 0){
    header("Location: tambah_akun.php?error=Username sudah digunakan");
    exit();
}

// Insert akun
$query = "INSERT INTO users (username, email, password, role, participant_id)
          VALUES ('$username', '$email', '$password', 'user', '$participant_id')";

if(mysqli_query($koneksi, $query)){
    header("Location: tambah_akun.php?success=1");
} else {
    header("Location: tambah_akun.php?error=Gagal menyimpan ke database");
}
exit();
?>
