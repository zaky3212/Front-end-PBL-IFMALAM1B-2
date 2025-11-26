<?php 
include '../koneksi.php';
session_start();

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM minutes WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lihat Notulen</title>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: rgba(0,0,0,0.6);
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.modal-content {
  background: #fff;
  width: 650px;
  border-radius: 12px;
  padding: 25px;
  animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
.modal-header h2 {
  margin: 0 0 15px 0;
  padding-bottom: 10px;
  border-bottom: 2px solid #ddd;
}
.info-group {
  margin-bottom: 15px;
}
.info-label {
  font-weight: 600;
  margin-bottom: 6px;
}
.info-value {
  font-size: 14px;
  border: 1px solid #ccc;
  padding: 8px;
  border-radius: 6px;
  background: #f5f5f5;
}
.btn {
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  border: none;
  font-weight: 600;
}
.btn-close {
  background: #aaa;
  color: #fff;
}
.btn-download {
  background: #00c3ff;
  color: #000;
}
.button-group {
  text-align: right;
  margin-top: 20px;
}
</style>
</head>
<body>

<div class="modal-content">
    <div class="modal-header">
        <h2>Detail Notulen</h2>
    </div>
    
    <div class="modal-body">

        <div class="info-group">
            <div class="info-label">Judul Notulen</div>
            <div class="info-value"><?= $data['title'] ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Agenda</div>
            <div class="info-value"><?= $data['agenda'] ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Pembahasan</div>
            <div class="info-value"><?= $data['notes'] ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Keputusan</div>
            <div class="info-value"><?= $data['decisions'] ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Tindak Lanjut</div>
            <div class="info-value"><?= $data['follow_up'] ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Tanggal Rapat</div>
            <div class="info-value"><?= date("d M Y", strtotime($data['created_at'])) ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Pembuat Notulen</div>
            <div class="info-value"><?= $data['created_by'] ?></div>
        </div>

    </div>

    <div class="button-group">
        <button class="btn btn-close" onclick="window.history.back()">Tutup</button>
        <button class="btn btn-download" onclick="window.location='download_notulen.php?id=<?= $id ?>'">Download</button>
    </div>

</div>

</body>
</html>
