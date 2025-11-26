<?php
include '../koneksi.php';

// Ambil semua data rapat
$sql = "SELECT * FROM meetings ORDER BY dates DESC";
$result = mysqli_query($koneksi, $sql);
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
    .content-section {
      margin-top: 30px;
    }
    .content-section h4 {
      font-weight: 600;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    th {
      text-align: left;
      padding: 12px;
      border-bottom: 1px solid #ddd;
      font-weight: 600;
    }
    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
    }
    tr:nth-child(even) {
      background-color: #f8f8f8;
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
      <h4>Jadwal Rapat</h4>

      <table>
        <thead>
          <tr>
            <th>Judul Rapat</th>
            <th>Leader</th>
            <th>Dokumentasi</th>
            <th>Ruang</th>
            <th>Waktu Mulai Rapat</th>
            <th>Waktu Selesai Rapat</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr> 
        </thead>
        <tbody>
          <?php 
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['title']}</td>
                      <td>{$row['leader']}</td>
                      <td>{$row['descriptions']}</td>
                      <td>{$row['locations']}</td>
                      <td>{$row['start_time']}</td>
                      <td>{$row['end_time']}</td>
                      <td>{$row['dates']}</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='6'>Belum ada jadwal rapat</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
