<?php
session_start();
include '../koneksi.php'; 

$admin_name = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Undangan Rapat - Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 260px;
      background-color: #f2e9dc;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      padding: 40px 25px;
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
    }

    .sidebar h2 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 50px;
      position: relative;
    }

    .sidebar h2::before {
      content: "";
      width: 5px;
      height: 25px;
      background-color: #f4ce14;
      position: absolute;
      left: -15px;
      top: 0;
      border-radius: 3px;
    }

    .sidebar .menu {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .menu a {
      text-decoration: none;
      color: #222;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 15px;
      border-radius: 10px;
      font-weight: 500;
      transition: 0.3s;
    }

    .menu a:hover,
    .menu a.active {
      background-color: #e6dccb;
  color: black;
      font-weight: 600;
    }

    .menu i {
      width: 20px;
      text-align: center;
    }

    .main {
      flex: 1;
      background-color: #fff;
      padding: 25px 40px;
      overflow-y: auto;
      margin-left: 260px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    .logout-box {
  margin-top: auto;
  padding-top: 40px;
}

.logout-btn {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  border-radius: 10px;
  background: #ffe3e3;
  color: #b30000;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid #ffb3b3;
  transition: 0.3s;
}


.logout-btn i {
  font-size: 18px;
}

.logout-btn:hover {
  background: #ff4d4d;
  color: #fff;
  border-color: #ff4d4d;
  transform: translateY(-2px);
  box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.25);
}
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <h2>Pengelolaan Rapat</h2>
    <h3>Selamat datang, <?= $admin_name ?>!</h3>
    <div class="menu">
      <a href="dashboard_admin.php"><i class="fas fa-home"></i> Home</a>
      <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
      <a href="peserta_admin.php"><i class="fas fa-users"></i> Peserta</a>
      <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
      <a href="undangan_admin.php" ><i class="fas fa-envelope"></i> Undangan</a>
      <a href="tambah_akun.php" ><i class="fas fa-envelope"></i> Tambah Akun</a>
    </div>
    <div class="logout-box">
    <a href="../logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    </div>
  </div>

  <!-- MAIN -->
  <div class="main">

    <div class="topbar">
      <h1>Kelola Undangan</h1>
      <i class="fas fa-bell"></i>
    </div>

    <!-- FORM KIRIM UNDANGAN -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="card-title mb-3 fw-bold">Kirim Undangan</h5>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $meeting_id = $_POST['meeting_id'];
          $participant_id = $_POST['participant_id'];
      
          // cek apakah peserta sudah diundang
          $cek = mysqli_query($koneksi, "SELECT * FROM meetings_participant 
                                         WHERE meeting_id='$meeting_id' 
                                         AND participant_id='$participant_id'");
      
          if (mysqli_num_rows($cek) > 0) {
              echo "<div class='alert alert-danger'>Peserta sudah diundang!</div>";
          } else {
              mysqli_query($koneksi, "INSERT INTO meetings_participant (meeting_id, participant_id, attendance_status) 
                                      VALUES ('$meeting_id', '$participant_id', 'pending')");
              echo "<div class='alert alert-success'>Undangan berhasil dikirim!</div>";
          }
      }

        ?>

        <form method="POST">

          <label class="fw-semibold">Pilih Rapat</label>
          <select name="meeting_id" class="form-select mb-3" required>
            <option value="">-- Pilih Rapat --</option>
            <?php
            $rapat = mysqli_query($koneksi, "SELECT * FROM meetings");
            while ($r = mysqli_fetch_assoc($rapat)) {
              echo "<option value='{$r['id']}'>{$r['title']}</option>";
            }
            ?>
          </select>

          <label class="fw-semibold">Pilih Peserta</label>
        <select name="participant_id" class="form-select mb-3" required>
            <option value="">-- Pilih Peserta --</option>
            <?php
            $peserta = mysqli_query($koneksi, "SELECT * FROM participant");
            while ($p = mysqli_fetch_assoc($peserta)) {
              echo "<option value='{$p['id']}'>{$p['name']} ({$p['email']})</option>";
            }
            ?>
          </select>

          <button class="btn btn-primary w-100">Kirim Undangan</button>

        </form>
      </div>
    </div>

    <!-- TABEL UNDANGAN -->
    <div class="card shadow-sm">
      <div class="card-body">

        <h5 class="fw-bold mb-3">Daftar Undangan</h5>

        <table class="table table-striped align-middle">
          <tr class="table-dark">
            <th>Judul Rapat</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>

          <?php
          $data = mysqli_query($koneksi, "
          SELECT mp.id as mpid, m.title, p.name, p.email, mp.attendance_status
          FROM meetings_participant mp
          INNER JOIN meetings m ON mp.meeting_id = m.id
          INNER JOIN participant p ON mp.participant_id = p.id
        ");

          while ($d = mysqli_fetch_assoc($data)) {

            if ($d['attendance_status'] == 'accepted') {
              $s = "<span class='badge bg-success'>Diterima</span>";
            } elseif ($d['attendance_status'] == 'declined') {
              $s = "<span class='badge bg-danger'>Ditolak</span>";
            } else {
              $s = "<span class='badge bg-secondary'>Pending</span>";
            }

            echo "
            <tr>
              <td>{$d['title']}</td>
              <td>{$d['name']}</td>
              <td>{$d['email']}</td>
              <td>$s</td>
              <td>
                <a href='undangan_edit.php?id={$d['mpid']}' class='btn btn-outline-primary btn-sm'>Edit</a>
                <a href='undangan_hapus.php?id={$d['mpid']}' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"Yakin menghapus undangan ini?\")'>Hapus</a>
              </td>
            </tr>
          ";
          }
          ?>
        </table>

      </div>
    </div>

  </div>

</body>

</html>