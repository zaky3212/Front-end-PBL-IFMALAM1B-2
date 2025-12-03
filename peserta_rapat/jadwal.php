<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

// Ambil semua data rapat
$sql = "SELECT * FROM meetings ORDER BY dates DESC";
$result = mysqli_query($koneksi, $sql);

// function status
function getStatus($dates, $start_time, $end_time)
{
  $now = time();
  $start = strtotime($dates . " " . $start_time);
  $end = strtotime($dates . " " . $end_time);

  if ($now > $end) return "Selesai";
  if ($now >= $start && $now <= $end) return "Berlangsung";
  return "Mendatang";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengelolaan Rapat - Jadwal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
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

    .sidebar h5 {
      font-weight: 600;
      margin-bottom: 30px;
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

    .sidebar a.active {
      background-color: #00eaff;
      color: #000;
    }

    .sidebar a i {
      margin-right: 10px;
    }

    .main-content {
      margin-left: 230px;
      padding: 30px 50px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
    }

    .search-bar input {
      border: 1px solid #ccc;
      border-radius: 25px;
      padding: 5px 15px;
      width: 200px;
    }

    .logout {
      color: #000;
      text-decoration: none;
      font-weight: 500;
    }

    .logout i {
      margin-left: 6px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }

    table tr:hover {
      background-color: #f3f3f3;
      transition: 0.2s;
    }

    .status {
      padding: 5px 10px;
      border-radius: 6px;
      font-weight: 500;
      font-size: 13px;
      color: white;
    }

    .selesai {
      background-color: #6c757d;
    }

    .berlangsung {
      background-color: #198754;
    }

    .mendatang {
      background-color: #0d6efd;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h5>Pengelolaan Rapat</h5>
    <h4 class="fw-bold mb-4">Peserta</h4>
    <a href="biodata.php"><i class="bi bi-house-door"></i>Home</a>
    <a href="undangan.php"><i class="bi bi-bookmark"></i>Undangan</a>
    <a href="jadwal.php" class="active"><i class="bi bi-calendar"></i>Jadwal</a>
    <a href="notulensi.php"><i class="bi bi-file-earmark-text"></i>Notulensi</a>
  </div>

  <div class="main-content">
    <div class="topbar">
      <div class="search-bar">
        <input type="text" placeholder="Search...">
      </div>
      <a href="../logout.php" class="logout">Logout <i class="bi bi-box-arrow-right"></i></a>
    </div>

    <div class="content-section">
      <h4 class="mb-3">Jadwal Rapat</h4>

      <table class="table">
        <thead>
          <tr>
            <th>Judul Rapat</th>
            <th>Leader</th>
            <th>Ruang</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

              $status = getStatus($row['dates'], $row['start_time'], $row['end_time']);
              $badgeClass = strtolower($status);

              echo "<tr>
                      <td>{$row['title']}</td>
                      <td>{$row['leader']}</td>
                      <td>{$row['locations']}</td>
                      <td>{$row['start_time']}</td>
                      <td>{$row['end_time']}</td>
                      <td>{$row['dates']}</td>

                      <td>
                        <span class='status $badgeClass'>$status</span>
                      </td>

                      <td>
  <a href='detail.php?id={$row['id']}' class='btn btn-sm btn-primary'>
    <i class='bi bi-eye'></i> Detail
  </a>
</td>

                    </tr>";
            }
          } else {
            echo "<tr><td colspan='8'>Belum ada jadwal rapat</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>