<?php 
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php'; 

$participant_id = $_SESSION['participant_id'];
$peserta_name = $_SESSION['username'];

// Update status kehadiran
if (isset($_GET['hadir'])) {
    $mpid = $_GET['hadir'];
    mysqli_query($koneksi, "
        UPDATE meetings_participant 
        SET attendance_status='accepted'
        WHERE id='$mpid' AND participant_id='$participant_id'
    ");
    header("Location: undangan.php");
    exit();
}

if (isset($_GET['tolak'])) {
    $mpid = $_GET['tolak'];
    mysqli_query($koneksi, "
        UPDATE meetings_participant 
        SET attendance_status='declined'
        WHERE id='$mpid' AND participant_id='$participant_id'
    ");
    header("Location: undangan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Undangan Rapat - Peserta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- CSS khusus undangan peserta -->
<link rel="stylesheet" href="../assets/style_undangan_peserta.css">

<style>
    /* ===== Global ===== */
body {
    font-family: "Poppins", sans-serif;
    background-color: #fff;
    margin: 0;
}

/* ===== Sidebar ===== */
.sidebar {
    width: 260px;
    background-color: #f2e9dc;
    display: flex;
    flex-direction: column;
    padding: 40px 25px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
}

.sidebar h5 {
    margin: 0;
    font-size: 16px;
    opacity: 0.7;
}

.sidebar h4 {
    font-weight: 700;
    margin: 10px 0 40px;
}

.sidebar a {
    text-decoration: none;
    color: #222;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 500;
    margin-bottom: 5px;
    transition: 0.3s ease;
}

.sidebar a:hover,
.sidebar a.active {
    background-color: #e6dccb;
  color: black;
    font-weight: 600;
}

/* ===== Main Content ===== */
.main-content {
    margin-left: 260px;
    padding: 35px 45px;
}

/* Topbar */
.topbar h3 {
    margin: 0;
}

.topbar .btn {
    font-size: 13px;
}

/* ===== Card Undangan ===== */
.undangan-card {
    border-radius: 14px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.10);
    padding: 30px;
}

.undangan-title {
    font-weight: 700;
    margin-bottom: 25px;
}

/* Table */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.table th {
    font-weight: 600;
    color: #333;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    background-color: #fff;
    padding: 12px 15px;
}

.table tr:hover td {
    background-color: #f1f1f1;
    transition: 0.2s;
}

/* Tombol Terima/Tolak */
.btn-sm {
    font-size: 13px;
    padding: 4px 8px;
}


/* Container untuk menu link */
.sidebar .menu-links {
    display: flex;
    flex-direction: column;
}

/* Logout Box tetap di bawah */
.logout-box {
    margin-top: 20px;
}

.logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    width: 100%;
    background-color: #ff4d4f; /* merah untuk menandakan logout */
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: #ff7875;
}

/* ===============================
   RESPONSIVE UNDANGAN PESERTA
   (SAMA DENGAN BIODATA & JADWAL)
================================ */

/* HAMBURGER */
.hamburger {
  display: none;
  font-size: 26px;
  background: none;
  border: none;
  cursor: pointer;
}

/* OVERLAY */
.overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  z-index: 1500;
}

/* MOBILE MODE */
@media (max-width: 768px) {

  body {
    overflow-x: hidden;
  }

  /* HAMBURGER MUNCUL */
  .hamburger {
    display: block;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 3000;
  }

  /* SIDEBAR SLIDE */
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 3000;
  }

  .sidebar.active {
    transform: translateX(0);
  }

  /* OVERLAY */
  .overlay.active {
    display: block;
  }

  /* MAIN CONTENT */
  .main-content {
    margin-left: 0;
    padding: 20px 15px;
  }

  /* TABLE RESPONSIVE */
  .table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  .table th,
  .table td {
    font-size: 13px;
    padding: 10px;
  }
}

</style>
</head>
<body>
<button class="hamburger" id="hamburgerBtn">☰</button>
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<div class="sidebar">
    <div class="menu-links">
        <h5>Pengelolaan Rapat</h5>
        <h4 class="fw-bold mb-4">Selamat Datang  <?= $peserta_name ?>!</h4>
        <a href="biodata.php"><i class="bi bi-house-door"></i> Home</a>
        <a href="undangan.php"><i class="bi bi-bookmark"></i> Undangan</a>
        <a href="jadwal.php"><i class="bi bi-calendar"></i> Jadwal</a>
        <a href="notulensi.php"><i class="bi bi-file-earmark-text"></i> Notulensi</a>
    </div>

    <!-- Logout Box -->
    <div class="logout-box">
        <a href="../logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center mb-4">
        <h3>Undangan Rapat</h3>
      
    </div>

    <div class="card shadow-sm p-4 undangan-card">
        <h4 class="mb-3 fw-bold undangan-title">Daftar Undangan Rapat</h4>

        <table class="table table-hover align-middle">
            <thead class="table-light">
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
                WHERE mp.participant_id = '$participant_id'
                ORDER BY m.dates DESC
            ");

            if(mysqli_num_rows($data) > 0){
                while ($d = mysqli_fetch_assoc($data)) {
                    $status = match($d['attendance_status']) {
                        'accepted' => "<span class='badge bg-success'>Diterima</span>",
                        'declined' => "<span class='badge bg-danger'>Ditolak</span>",
                        default => "<span class='badge bg-secondary'>Pending</span>",
                    };

                    echo "
                    <tr>
                        <td>{$d['title']}</td>
                        <td>{$d['date']}</td>
                        <td>{$d['time']}</td>
                        <td>$status</td>
                        <td>
                            <a href='undangan.php?hadir={$d['mpid']}' class='btn btn-success btn-sm' ".($d['attendance_status']!='pending'?'disabled':'').">Terima</a>
                            <a href='undangan.php?tolak={$d['mpid']}' class='btn btn-danger btn-sm' ".($d['attendance_status']!='pending'?'disabled':'').">Tolak</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Belum ada undangan rapat.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.getElementById('hamburgerBtn');
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('overlay');

  if (!hamburger || !sidebar || !overlay) return;

  hamburger.addEventListener('click', () => {
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
  document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
});

overlay.addEventListener('click', () => {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  document.body.style.overflow = 'auto';
});

document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('overlay');

  // Reset sidebar dan overlay saat halaman dimuat
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  document.body.style.overflow = 'auto';
});

});
</script>

</body>
</html>
