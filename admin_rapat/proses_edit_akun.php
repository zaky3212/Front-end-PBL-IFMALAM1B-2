<?php
session_start();
include '../koneksi.php';

// Cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil data POST
$id       = intval($_POST['id']);
$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = $_POST['password'];

// Validasi dasar
if ($username == '' || $email == '') {
    echo "<script>
            alert('Username dan Email wajib diisi!');
            window.history.back();
          </script>";
    exit();
}

// Cek email duplikat (kecuali akun sendiri)
$cek = mysqli_query($koneksi, "
    SELECT id FROM users 
    WHERE email='$email' AND id != '$id'
");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
            alert('Email sudah digunakan!');
            window.history.back();
          </script>";
    exit();
}

// Jika password diisi → update password
if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($koneksi, "
        UPDATE users SET
        username='$username',
        email='$email',
        password='$password_hash'
        WHERE id='$id'
    ");
} 
// Jika password kosong → jangan update password
else {
    $query = mysqli_query($koneksi, "
        UPDATE users SET
        username='$username',
        email='$email'
        WHERE id='$id'
    ");
}

// Cek hasil query
if ($query) {
    echo "<script>
            alert('Akun peserta berhasil diperbarui!');
            window.location='tambah_akun.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal memperbarui akun!');
            window.history.back();
          </script>";
}
