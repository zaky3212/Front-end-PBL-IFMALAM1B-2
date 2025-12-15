<?php
session_start();
include '../koneksi.php';

/* VALIDASI WAJIB */
if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['department']) ||
    empty($_POST['position']) ||
    empty($_POST['phone'])
) {
    header("Location: peserta_admin.php?error=data_kosong");
    exit();
}

$name       = mysqli_real_escape_string($koneksi, $_POST['name']);
$email      = mysqli_real_escape_string($koneksi, $_POST['email']);
$department = mysqli_real_escape_string($koneksi, $_POST['department']);
$position   = mysqli_real_escape_string($koneksi, $_POST['position']);
$phone      = mysqli_real_escape_string($koneksi, $_POST['phone']);

/* CEK EMAIL SUDAH ADA */
$cek = mysqli_query($koneksi, "SELECT id FROM participant WHERE email='$email'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: peserta_admin.php?error=email_sudah_ada");
    exit();
}

/* INSERT PARTICIPANT */
$sql = "INSERT INTO participant(name,email,department,position,phone)
        VALUES('$name','$email','$department','$position','$phone')";

$result = mysqli_query($koneksi, $sql);

if ($result) {

    $participant_id = mysqli_insert_id($koneksi);

    /* BUAT AKUN USER OTOMATIS */
    $password = password_hash("123456", PASSWORD_DEFAULT);
    $role = "user";

    mysqli_query($koneksi, "
        INSERT INTO users(username,password,role,participant_id)
        VALUES('$email','$password','$role','$participant_id')
    ");

    header("Location: peserta_admin.php?success=1");
    exit();

} else {
    header("Location: peserta_admin.php?error=gagal_simpan");
    exit();
}
