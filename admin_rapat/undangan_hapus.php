<?php
include '../koneksi.php';
$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM meetings_participant WHERE id='$id'");
header("location:undangan_admin.php");
?>
