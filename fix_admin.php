<?php
include "koneksi.php";

$email = "admin@mail.com";
$newPassword = "123456";

// generate hash
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// update database
mysqli_query($koneksi, "UPDATE users SET password='$passwordHash' WHERE email='$email'");

echo "PASSWORD ADMIN BERHASIL DI-HASH";
