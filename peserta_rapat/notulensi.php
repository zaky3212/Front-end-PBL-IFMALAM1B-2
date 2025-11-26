<?php 
include '../koneksi.php';
session_start();

// Ambil kata pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query data notulen dari database
$sql = "SELECT * FROM minutes 
        WHERE title LIKE '%$search%' 
        OR created_by LIKE '%$search%' 
        OR agenda LIKE '%$search%'
        ORDER BY created_at DESC";

$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notulensi Rapat</title>
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

.topbar input {
  border: 1px solid #888;
  border-radius: 6px;
  padding: 5px 10px;
  outline: none;
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

<div class="sidebar">
  <h5>Pengelolaan Rapat</h5>
  <h4 class="fw-bold mb-4">Peserta</h4>
  <a href="biodata.php"><i class="bi bi-house-door"></i>Home</a>
  <a href="undangan.php"><i class="bi bi-bookmark"></i>Undangan</a>
  <a href="jadwal.php"><i class="bi bi-calendar"></i>Jadwal</a>
  <a href="notulensi.php" class="active"><i class="bi bi-file-earmark-text"></i>Notulensi</a>
</div>

<div class="main-content">

  <div class="topbar">
    <form method="GET">
      <input type="text" name="search" placeholder="Search..." value="<?= $search ?>">
    </form>
    <a href="../logout.php" class="logout">Logout <i class="bi bi-box-arrow-right"></i></a>
  </div>

  <h4>NOTULENSI</h4>

  <table>
    <thead>
      <tr>
        <th>Judul Rapat</th>
        <th>Pemimpin</th>
        <th>Agenda</th>
        <th>Tanggal</th>
        <th>Pembuat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
              <td><?= $row['title'] ?></td>
              <td><?= $row['created_by'] ?></td>
              <td><?= $row['agenda'] ?></td>
              <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
              <td><?= $row['created_by'] ?></td>
              <td>
                <a href="view_notulen.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Lihat</a>
                <a href="download_notulen.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Download</a>
              </td>
            </tr>
      <?php   }
      } else {
          echo "<tr><td colspan='6' class='text-center'>Tidak ada data</td></tr>";
      }
      ?>
    </tbody>
  </table>

</div>
</body>
</html>
