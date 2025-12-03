<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Query user berdasarkan EMAIL
    $sql = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $data = mysqli_fetch_assoc($sql);

    // Validasi password hash
    if ($data && password_verify($password, $data['password'])) {

        // SET SESSION yang benar
        $_SESSION['user_id'] = $data['participant_id'];
        $_SESSION["username"] = $data['username'];
        $_SESSION["email"] = $data['email'];
        $_SESSION["role"] = $data['role'];
        $_SESSION["login"] = true;

        // Redirect sesuai role
        if ($data['role'] == 'admin') {
            header("Location: admin_rapat/dashboard_admin.php");
        } else {
            header("Location: peserta_rapat/biodata.php");
        }
        exit();
    } 
    else {
        echo "<script>alert('Email atau Password salah!');window.location='login.php';</script>";
    }
}
?>
