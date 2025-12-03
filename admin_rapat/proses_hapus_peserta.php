<?php
session_start();
include '../koneksi.php';

$id = intval($_GET['id']);

mysqli_query($koneksi, "DELETE FROM participant WHERE id=$id");

header("Location: peserta_admin.php?success=3");
exit();
?>
