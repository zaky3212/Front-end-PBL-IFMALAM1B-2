<?php

// Ambil data POST dengan sanitasi dasar
$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$descriptions = isset($_POST['descriptions']) ? trim($_POST['descriptions']) : '';
$dates = isset($_POST['dates']) ? trim($_POST['dates']) : '';
$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '';
$locations = isset($_POST['locations']) ? trim($_POST['locations']) : '';
$leader = isset($_POST['leader']) ? trim($_POST['leader']) : '';
$status_meetings = isset($_POST['status_meetings']) ? trim($_POST['status_meetings']) : 'Mendatang';
$created_by = isset($_POST['created_by']) ? trim($_POST['created_by']) : 'admin';
$created_at = date('Y-m-d H:i:s');

include '../koneksi.php';

// Escape untuk keamanan dasar
$title = mysqli_real_escape_string($koneksi, $title);
$descriptions = mysqli_real_escape_string($koneksi, $descriptions);
$dates = mysqli_real_escape_string($koneksi, $dates);
$start_time = mysqli_real_escape_string($koneksi, $start_time);
$end_time = mysqli_real_escape_string($koneksi, $end_time);
$locations = mysqli_real_escape_string($koneksi, $locations);
$leader = mysqli_real_escape_string($koneksi, $leader);
$status_meetings = mysqli_real_escape_string($koneksi, $status_meetings);
$created_by = mysqli_real_escape_string($koneksi, $created_by);

// Jika id kosong, biarkan DB yang mengatur AUTO_INCREMENT (tidak menyertakan kolom id)
if ($id === '') {
    $sql = "INSERT INTO meetings(title,descriptions,dates,start_time,end_time,locations,leader,status_meetings,created_by,created_at) ";
    $sql .= "VALUES ('$title','$descriptions','$dates','$start_time','$end_time','$locations','$leader','$status_meetings','$created_by','$created_at')";
} else {
    $id = mysqli_real_escape_string($koneksi, $id);
    $sql = "INSERT INTO meetings(id,title,descriptions,dates,start_time,end_time,locations,leader,status_meetings,created_by,created_at) ";
    $sql .= "VALUES ('$id','$title','$descriptions','$dates','$start_time','$end_time','$locations','$leader','$status_meetings','$created_by','$created_at')";
}

$query = mysqli_query($koneksi, $sql);
if ($query) {
    header('Location: jadwal_admin.php');
    exit;
} else {
    echo "<script>alert('Maaf data tidak tersimpan: " . mysqli_error($koneksi) . "'); window.location.replace('jadwal_admin.php');</script>";
    exit;
}

?>