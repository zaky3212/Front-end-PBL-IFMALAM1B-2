<?php
session_start();
include '../koneksi.php';

if (!isset($_GET['id'])) {
  header("Location: undangan_admin.php");
  exit();
}

$id = intval($_GET['id']);

// Ambil data undangan
$query = mysqli_query($koneksi, "
  SELECT * FROM meetings_participant WHERE id = $id
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
  die("Data undangan tidak ditemukan");
}

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $meeting_id = $_POST['meeting_id'];
  $participant_id = $_POST['participant_id'];
  $status = $_POST['attendance_status'];

  // Cek duplikasi (kecuali data ini sendiri)
  $cek = mysqli_query($koneksi, "
    SELECT * FROM meetings_participant 
    WHERE meeting_id='$meeting_id' 
    AND participant_id='$participant_id'
    AND id != $id
  ");

  if (mysqli_num_rows($cek) > 0) {
    $error = "Peserta sudah terdaftar pada rapat tersebut!";
  } else {
    mysqli_query($koneksi, "
      UPDATE meetings_participant SET
        meeting_id='$meeting_id',
        participant_id='$participant_id',
        attendance_status='$status'
      WHERE id=$id
    ");

    header("Location: undangan_admin.php?success=update");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Undangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-body">

      <h4 class="fw-bold mb-3">Edit Undangan Rapat</h4>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">

        <!-- Pilih Rapat -->
        <label class="fw-semibold">Rapat</label>
        <select name="meeting_id" class="form-select mb-3" required>
          <?php
          $rapat = mysqli_query($koneksi, "SELECT * FROM meetings");
          while ($r = mysqli_fetch_assoc($rapat)) {
            $selected = ($r['id'] == $data['meeting_id']) ? 'selected' : '';
            echo "<option value='{$r['id']}' $selected>{$r['title']}</option>";
          }
          ?>
        </select>

        <!-- Pilih Peserta -->
        <label class="fw-semibold">Peserta</label>
        <select name="participant_id" class="form-select mb-3" required>
          <?php
          $peserta = mysqli_query($koneksi, "SELECT * FROM participant");
          while ($p = mysqli_fetch_assoc($peserta)) {
            $selected = ($p['id'] == $data['participant_id']) ? 'selected' : '';
            echo "<option value='{$p['id']}' $selected>{$p['name']} ({$p['email']})</option>";
          }
          ?>
        </select>

        <!-- Status Kehadiran -->
        <label class="fw-semibold">Status Kehadiran</label>
        <select name="attendance_status" class="form-select mb-4" required>
          <option value="pending" <?= $data['attendance_status']=='pending'?'selected':'' ?>>Pending</option>
          <option value="accepted" <?= $data['attendance_status']=='accepted'?'selected':'' ?>>Diterima</option>
          <option value="declined" <?= $data['attendance_status']=='declined'?'selected':'' ?>>Ditolak</option>
        </select>

        <div class="d-flex gap-2">
          <button class="btn btn-primary">Simpan Perubahan</button>
          <a href="undangan_admin.php" class="btn btn-secondary">Kembali</a>
        </div>

      </form>

    </div>
  </div>
</div>

</body>
</html>
