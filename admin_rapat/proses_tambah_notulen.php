<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id         = $_POST['id'] ?? '';
    $title      = mysqli_real_escape_string($koneksi, $_POST['title']);
    $agenda     = mysqli_real_escape_string($koneksi, $_POST['agenda']);
    $notes      = mysqli_real_escape_string($koneksi, $_POST['notes']);
    $decisions  = mysqli_real_escape_string($koneksi, $_POST['decisions']);
    $follow_up  = mysqli_real_escape_string($koneksi, $_POST['follow_up']);
    $created_by = mysqli_real_escape_string($koneksi, $_POST['created_by']);

    if ($id) {
        // UPDATE
        $query = "UPDATE minutes SET
      title = '$title',
      agenda = '$agenda',
      notes = '$notes',
      decisions = '$decisions',
      follow_up = '$follow_up',
      created_by = '$created_by'
      WHERE id = '$id'";
    } else {
        // INSERT
        $query = "INSERT INTO minutes
      (title, agenda, notes, decisions, follow_up, created_by, status, created_at)
      VALUES
      ('$title', '$agenda', '$notes', '$decisions', '$follow_up', '$created_by', 'draft', NOW())";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: notulen_admin.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
