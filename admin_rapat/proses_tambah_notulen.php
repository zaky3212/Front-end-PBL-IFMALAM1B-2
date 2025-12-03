<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $agenda = mysqli_real_escape_string($koneksi, $_POST['agenda']);
    $decisions = mysqli_real_escape_string($koneksi, $_POST['decisions']);
    $follow_up = mysqli_real_escape_string($koneksi, $_POST['follow_up']);
    $notes = mysqli_real_escape_string($koneksi, $_POST['notes']);
    $created_by = mysqli_real_escape_string($koneksi, $_POST['created_by']);
    
    // Jika ada ID, berarti edit, jika tidak berarti tambah baru
    if (!empty($_POST['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_POST['id']);
        $query = "UPDATE participant SET 
                  title = '$title',
                  agenda = '$agenda',
                  decisions = '$decisions',
                  follow_up = '$follow_up',
                  notes = '$notes',
                  created_by = '$created_by',
                  created_at = NOW()
                  WHERE id = '$id'";
    } else {
        $query = "INSERT INTO minutes (title, agenda, decisions, follow_up, notes, created_by, created_at) 
                  VALUES ('$title', '$agenda', '$decisions', '$follow_up', '$notes', '$created_by', NOW())";
    }
    
    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Data notulen berhasil disimpan!');
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