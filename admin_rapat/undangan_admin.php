<?php include '../koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Rapat - Admin</title>

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

      /* Sidebar */
      .sidebar {
        width: 260px;
        background-color: #f2e9dc;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 40px 25px;
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

      .menu a {
        text-decoration: none;
        color: #222;
        display: block;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: 500;
        margin-bottom: 10px;
        transition: 0.3s;
      }

      .menu a:hover,
      .menu a.active {
        background-color: #00f7ff;
        font-weight: 600;
      }

      /* Main */
      .main {
        flex: 1;
        background-color: white;
        padding: 25px 40px;
        overflow-y: auto;
      }

      .topbar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
      }

      .topbar h1 {
        font-size: 30px;
        font-weight: 700;
      }

      .form-box, .table-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 30px;
      }

      label {
        font-weight: 600;
      }

      select, button {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        border-radius: 8px;
        border: 1px solid #ccc;
      }

      button {
        background: #00c2ff;
        color: #fff;
        border: none;
        cursor: pointer;
        margin-top: 15px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
      }

      table th, table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
      }

      table th {
        background: #333;
        color: white;
      }

    </style>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Pengelolaan Rapat</h2>

  <div class="menu">
    <a href="dashboard_admin.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
    <a href="peserta_admin.php"><i class="fas fa-users"></i> Peserta</a>
    <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
    <a href="undangan_admin.php" class="active"><i class="fas fa-envelope"></i> Undangan</a>
  </div>
</div>

<!-- Main -->
<div class="main">

  <div class="topbar">
    <h1>Kelola Undangan</h1>
    <i class="fas fa-bell" style="font-size:22px;"></i>
  </div>

  <!-- FORM KIRIM UNDANGAN -->
  <div class="form-box">
    <h3>Kirim Undangan ke Peserta</h3>

    <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $meeting = $_POST['meeting_id'];
        $participant = $_POST['participant_id'];

        $cek = mysqli_query($koneksi, "SELECT * FROM meetings_participant 
                                      WHERE meeting_id='$meeting' AND participant_id='$participant'");

        if (mysqli_num_rows($cek) > 0) {
            echo "<p style='color:red;font-weight:600;'>Peserta sudah diundang!</p>";
        } else {
            $insert = mysqli_query($koneksi, "INSERT INTO meetings_participant
                      (meeting_id, participant_id, attendance_status) 
                      VALUES ('$meeting', '$participant', 'pending')");
            echo "<p style='color:green;font-weight:600;'>Undangan berhasil dikirim!</p>";
        }
      }
    ?>

    <form method="POST">
      <label>Pilih Rapat</label>
      <select name="meeting_id" required>
        <option value="">-- Pilih Rapat --</option>

        <?php
        $rapat = mysqli_query($koneksi, "SELECT * FROM meetings");
        while ($r = mysqli_fetch_assoc($rapat)) {
          echo "<option value='{$r['id']}'>{$r['title']}</option>";
        }
        ?>
      </select>

      <label>Pilih Peserta</label>
      <select name="participant_id" required>
        <option value="">-- Pilih Peserta --</option>

        <?php
        $peserta = mysqli_query($koneksi, "SELECT * FROM participant");
        while ($p = mysqli_fetch_assoc($peserta)) {
          echo "<option value='{$p['id']}'>{$p['name']} ({$p['email']})</option>";
        }
        ?>
      </select>

      <button type="submit">Kirim Undangan</button>
    </form>
  </div>

  <!-- TABLE UNDANGAN -->
  <div class="table-box">
    <h3>Daftar Undangan Terkirim</h3>

    <table>
      <tr>
        <th>Judul Rapat</th>
        <th>Nama Peserta</th>
        <th>Email</th>
        <th>Status</th>
      </tr>

      <?php
      $data = mysqli_query($koneksi, "
        SELECT m.title, p.name, p.email, mp.attendance_status
        FROM meetings_participant mp
        INNER JOIN meetings m ON mp.meeting_id = m.id
        INNER JOIN participant p ON mp.participant_id = p.id
      ");

      while ($d = mysqli_fetch_assoc($data)) {
        echo "
          <tr>
            <td>{$d['title']}</td>
            <td>{$d['name']}</td>
            <td>{$d['email']}</td>
            <td>{$d['attendance_status']}</td>
          </tr>
        ";
      }
      ?>

    </table>
  </div>

</div>

</body>
</html>
