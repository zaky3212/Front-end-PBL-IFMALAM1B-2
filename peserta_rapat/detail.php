<?php
include '../koneksi.php';

if (!isset($_GET['id'])) {
    die("ERROR: ID rapat tidak ditemukan");
}

$id = $_GET['id'];

$sql = "SELECT * FROM meetings WHERE id='$id'";
$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("ERROR: Data rapat tidak tersedia");
}

function getStatus($dates, $start_time, $end_time) {
    $now = time();
    $start = strtotime($dates . " " . $start_time);
    $end = strtotime($dates . " " . $end_time);

    if ($now > $end) return "Selesai";
    if ($now >= $start && $now <= $end) return "Berlangsung";
    return "Mendatang";
}

$status = getStatus($data['dates'], $data['start_time'], $data['end_time']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Rapat</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body { font-family: 'Poppins', sans-serif; background: #ffffff; }
  .container { margin-top: 50px; }
  .status {
      padding: 5px 12px;
      border-radius: 6px;
      font-weight: 500;
      font-size: 14px;
      color: white;
  }
  .selesai { background-color: #6c757d; }
  .berlangsung { background-color: #198754; }
  .mendatang { background-color: #0d6efd; }
</style>
</head>

<body>
<div class="container">

  <a href="jadwal.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali</a>

  <div class="card p-4">

    <h3 class="fw-bold mb-3">
      <?= $data['title']; ?>
    </h3>

    <p><strong>Leader:</strong> <?= $data['leader']; ?></p>
    <p><strong>Ruang:</strong> <?= $data['locations']; ?></p>
    <p><strong>Tanggal:</strong> <?= $data['dates']; ?></p>
    <p><strong>Mulai:</strong> <?= $data['start_time']; ?></p>
    <p><strong>Selesai:</strong> <?= $data['end_time']; ?></p>

    <p><strong>Status:</strong> 
      <span class="status <?= strtolower($status); ?>"><?= $status ?></span>
    </p>

    <p><strong>Deskripsi / Dokumentasi:</strong><br> 
      <?= $data['descriptions']; ?>
    </p>

    <?php if ($status == "Berlangsung") : ?>
        <a href="<?= $data['meeting_link']; ?>" target="_blank" class="btn btn-success mt-3">
            <i class="bi bi-camera-video"></i> Masuk Rapat
        </a>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
