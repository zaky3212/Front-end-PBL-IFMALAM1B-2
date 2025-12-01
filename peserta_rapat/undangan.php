<?php 
session_start();
include '../koneksi.php'; 

// ==========================
// TIDAK MENGGUNAKAN SESSION
// ==========================

// Ambil user dari GET
if (isset($_GET['user'])) {
    $id = $_GET['user']; 
} else {
    die("ERROR: user tidak ditemukan");
}

// === JIKA PESERTA MENEKAN TERIMA ===
if (isset($_GET['hadir'])) {
    $mpid = $_GET['hadir'];
    mysqli_query($koneksi, "
        UPDATE meetings_participant 
        SET attendance_status='accepted', confirm_at=NOW() 
        WHERE id='$mpid'
    ");

    header("Location: undangan.php?user=$id");
    exit();
}

// === JIKA PESERTA MENEKAN TOLAK ===
if (isset($_GET['tolak'])) {
    $mpid = $_GET['tolak'];
    mysqli_query($koneksi, "
        UPDATE meetings_participant 
        SET attendance_status='declined', confirm_at=NOW() 
        WHERE id='$mpid'
    ");

    header("Location: undangan.php?user=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengelolaan Rapat - Undangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
      margin: 0;
      padding: 0;
    }
    .sidebar {
      width: 230px;
      background-color: #f6ebdf;
      min-height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      padding: 25px 20px;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 10px 12px;
      border-radius: 8px;
      text-decoration: none;
      color: #000;
      font-weight: 500;
      margin-bottom: 10px;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #00eaff;
      color: #000;
    }
    .main-content {
      margin-left: 230px;
      padding: 30px 50px;
      min-height: 100vh;
    }
  </style>
</head>


<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h5>Pengelolaan Rapat</h5>
    <h4 class="fw-bold mb-4">Peserta</h4>
    <a href="biodata.php?user=<?php echo $id ?>"><i class="bi bi-house-door"></i>Home</a>
    <a href="undangan.php?user=<?php echo $id ?>" class="active"><i class="bi bi-bookmark"></i>Undangan</a>
    <a href="jadwal.php?user=<?php echo $id ?>"><i class="bi bi-calendar"></i>Jadwal</a>
    <a href="notulensi.php?user=<?php echo $id ?>"><i class="bi bi-file-earmark-text"></i>Notulensi</a>
  </div>


  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h3>Undangan Rapat</h3>
      <a href="../logout.php" class="logout">Logout <i class="bi bi-box-arrow-right"></i></a>
    </div>

    <h5>Daftar Undangan Rapat</h5>
    <hr>

    <table class="table table-bordered table-striped mt-3">
      <thead class="table-dark">
        <tr>
          <th>Judul Rapat</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $data = mysqli_query($koneksi, "
            SELECT m.title, m.dates AS date, m.start_time AS time,
                   mp.attendance_status, mp.id as mpid
            FROM meetings_participant mp
            INNER JOIN meetings m ON mp.meeting_id = m.id
            WHERE mp.participant_id = '$id'
            ORDER BY m.dates DESC
        ");

        while ($d = mysqli_fetch_assoc($data)) {

          if ($d['attendance_status'] == 'accepted') {
            $status = "<span class='badge bg-success'>Diterima</span>";
          } elseif ($d['attendance_status'] == 'declined') {
            $status = "<span class='badge bg-danger'>Ditolak</span>";
          } else {
            $status = "<span class='badge bg-secondary'>Pending</span>";
          }

          echo "
            <tr>
              <td>{$d['title']}</td>
              <td>{$d['date']}</td>
              <td>{$d['time']}</td>
              <td>$status</td>
              <td>
                <a href='undangan.php?user=$id&hadir={$d['mpid']}' class='btn btn-success btn-sm' ".($d['attendance_status']!='pending'?'disabled':'').">Terima</a>
                <a href='undangan.php?user=$id&tolak={$d['mpid']}' class='btn btn-danger btn-sm' ".($d['attendance_status']!='pending'?'disabled':'').">Tolak</a>
              </td>
            </tr>";
        }
        ?>
      </tbody>
    </table>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
