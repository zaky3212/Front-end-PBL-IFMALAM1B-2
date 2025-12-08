<?php 
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$admin_name = $_SESSION['username'];

include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun Peserta</title>
  <link rel="stylesheet" href="../assets/style_dashboard_admin.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    .form-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        width: 600px;
        margin-top: 20px;
    }
    .form-box label {
        font-weight: 600;
        margin-top: 15px;
        display: block;
    }
    .form-box input, .form-box select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 8px;
        border: 1px solid #ccc;
        background: #f9f9f9;
    }
    .form-box button {
        margin-top: 20px;
        padding: 10px 20px;
        border-radius: 8px;
        background: #00f7ff;
        border: none;
        font-weight: 700;
        cursor: pointer;
    }
    .form-box button:hover {
        background: #14e0f4;
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

<div class="sidebar">
    <h2>Pengelolaan Rapat</h2>
    <h3>Selamat datang, <?= $admin_name ?>!</h3>
    <div class="menu">
      <a href="dashboard_admin.php"><i class="fas fa-home"></i> Home</a>
      <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
      <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
      <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
      <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
      <a  href="tambah_akun.php"><i class="fas fa-user-plus"></i> Tambah Akun</a>
    </div>
    <div class="logout-box">
    <a href="../logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    </div>
</div>

<div class="main">
  <div class="topbar">
    <h1>Buat Akun Peserta</h1>
    <div class="right">
      <div class="search-box">
        <input type="text" placeholder="Search...">
        <i class="fas fa-search"></i>
      </div>
      <i class="fas fa-bell bell"></i>
    </div>
  </div>

  <div class="form-box">

  <!-- Notifikasi -->
  <?php if(isset($_GET['success'])) { ?>
      <div style="background:#d4edda;padding:10px;border-radius:6px;color:#155724;margin-bottom:15px;">
          ✅ Akun peserta berhasil dibuat!
      </div>
  <?php } ?>

  <?php if(isset($_GET['error'])) { ?>
      <div style="background:#f8d7da;padding:10px;border-radius:6px;color:#721c24;margin-bottom:15px;">
          ❗ Terjadi kesalahan: <?= htmlspecialchars($_GET['error']) ?>
      </div>
  <?php } ?>

  <form action="proses_buat_akun_peserta.php" method="POST">
      <label>Pilih Peserta *</label>
      <select name="participant_id" required>
          <option value="">-- Pilih Peserta --</option>
          <?php
          $q = mysqli_query($koneksi, "SELECT * FROM participant ORDER BY name ASC");
          while($p = mysqli_fetch_assoc($q)){
              echo "<option value='".$p['id']."'>".$p['name']." (".$p['email'].")</option>";
          }
          ?>
      </select>

      <label>Email Akun *</label>
      <input type="email" name="email" required>

      <label>Username Akun *</label>
      <input type="text" name="username" required>

      <label>Password *</label>
      <input type="password" name="password" required>

      <button type="submit">Buat Akun</button>
  </form>

  </div>
</div>

</body>
</html>
