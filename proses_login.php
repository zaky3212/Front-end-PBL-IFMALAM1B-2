<?php
session_start();
// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Sanitasi sederhana untuk query
$username_esc = mysqli_real_escape_string($koneksi, $username);

// Ambil user berdasarkan username saja, lalu cocokkan password di PHP
$sql = "SELECT * FROM users WHERE `username` = '$username_esc' LIMIT 1";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    // Tampilkan error untuk debugging (hapus/disable di production)
    die('Query error: ' . mysqli_error($koneksi));
}

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    $stored = isset($data['password']) ? $data['password'] : '';

    $password_ok = false;
    // Jika password disimpan menggunakan password_hash
    if (password_verify($password, $stored)) {
        $password_ok = true;
    } elseif ($password === $stored) {
        // fallback jika password disimpan plaintext (tidak direkomendasikan)
        $password_ok = true;
    }

    if ($password_ok) {
        session_start();
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] === 'admin') {
            header('Location: admin_rapat/dashboard_admin.php');
            exit;
        } elseif ($data['role'] === 'user') {
            header('Location: peserta_rapat/biodata.php');
            exit;
        } else {
            // role tidak dikenali
            echo "<script>alert('Role tidak dikenali.'); window.location.replace('login.php');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Maaf, username atau password salah.'); window.location.replace('login.php');</script>";
        exit;
    }

} else {
    echo "<script>alert('Maaf, username tidak ditemukan.'); window.location.replace('login.php');</script>";
    exit;
}
?>