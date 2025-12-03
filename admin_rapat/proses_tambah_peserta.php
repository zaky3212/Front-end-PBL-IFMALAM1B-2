<?php
session_start();
include '../koneksi.php';

$name        = mysqli_real_escape_string($koneksi, $_POST['name']);
$email       = mysqli_real_escape_string($koneksi, $_POST['email']);
$department  = mysqli_real_escape_string($koneksi, $_POST['department']);
$position    = mysqli_real_escape_string($koneksi, $_POST['position']);
$phone       = mysqli_real_escape_string($koneksi, $_POST['phone']);

$sql = "INSERT INTO participant(name,email,department,position,phone)
        VALUES('$name','$email','$department','$position','$phone')";
$result = mysqli_query($koneksi, $sql);

if($result){
    $participant_id = mysqli_insert_id($koneksi);

    $password = password_hash("123456", PASSWORD_DEFAULT);
    $role = "user";

    mysqli_query($koneksi, "
        INSERT INTO users(username,password,role,participant_id)
        VALUES('$email','$password','$role','$participant_id')
    ");

    header("Location: peserta_admin.php?success=1");
    exit();
} else {
    header("Location: peserta_admin.php?error=1");
    exit();
}
?>
