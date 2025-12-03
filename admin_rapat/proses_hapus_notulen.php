<?php
session_start();
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $query = "DELETE FROM minutes WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Data notulen berhasil dihapus!');
            window.location.href = 'notulen_admin.php';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . mysqli_error($koneksi) . "');
            window.location.href = 'notulen_admin.php';
        </script>";
    }
} else {
    header("Location: notulen_admin.php");
}
?>