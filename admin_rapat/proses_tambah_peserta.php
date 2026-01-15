<?php
session_start();
include '../koneksi.php';

/* VALIDASI */
if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['department']) ||
    empty($_POST['position'])
) {
    header("Location: peserta_admin.php?error=1");
    exit();
}

$name       = mysqli_real_escape_string($koneksi, $_POST['name']);
$email      = mysqli_real_escape_string($koneksi, $_POST['email']);
$department = mysqli_real_escape_string($koneksi, $_POST['department']);
$position   = mysqli_real_escape_string($koneksi, $_POST['position']);
$phone      = mysqli_real_escape_string($koneksi, $_POST['phone'] ?? '');

/* CEK EMAIL PARTICIPANT */
$cekPeserta = mysqli_query($koneksi, "SELECT id FROM participant WHERE email='$email'");
if (mysqli_num_rows($cekPeserta) > 0) {
    header("Location: peserta_admin.php?error=email_sudah_ada");
    exit();
}

/* INSERT PARTICIPANT */
$sql = "INSERT INTO participant(name,email,department,position,phone)
        VALUES('$name','$email','$department','$position','$phone')";

if (mysqli_query($koneksi, $sql)) {

    //  ID participant BARU
    $participant_id = mysqli_insert_id($koneksi);

    //  CEK APAKAH USER SUDAH ADA
    $cekUser = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email'");

    //  JIKA BELUM ADA → INSERT TANPA PASSWORD
    if (mysqli_num_rows($cekUser) == 0) {
        mysqli_query($koneksi, "
            INSERT INTO users (username,email,role,participant_id,password)
            VALUES ('$email','$email','user','$participant_id', NULL)
        ");
    }

    header("Location: peserta_admin.php?success=1");
    exit();

} else {
    header("Location: peserta_admin.php?error=gagal_simpan");
    exit();
}
