<?php include 
session_start();
'../koneksi.php'; ?>

<?php
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT attendance_status FROM meetings_participant WHERE id='$id'");
$row = mysqli_fetch_assoc($data);
?>

<form method="POST">
  <label>Status Kehadiran</label>
  <select name="status">
    <option value="pending"  <?= ($row['attendance_status']=="pending")?"selected":""?>>pending</option>
    <option value="accepted" <?= ($row['attendance_status']=="accepted")?"selected":""?>>accepted</option>
    <option value="declined" <?= ($row['attendance_status']=="declined")?"selected":""?>>declined</option>
  </select>

  <button type="submit">Simpan</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $status = $_POST['status'];
  mysqli_query($koneksi, "UPDATE meetings_participant SET attendance_status='$status' WHERE id='$id'");
  header("location:undangan_admin.php");
}
?>
