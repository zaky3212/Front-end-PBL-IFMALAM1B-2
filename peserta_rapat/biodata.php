<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$user_id = $_SESSION['user_id'];
$peserta_name = $_SESSION['username'];

$query = mysqli_query($koneksi, "
    SELECT users.username, users.email AS user_email,
           participant.name, participant.email AS p_email,
           participant.phone, participant.department
    FROM users
    LEFT JOIN participant ON users.participant_id = participant.id
    WHERE users.id = '$user_id'
");

$peserta = mysqli_fetch_assoc($query);
if (!$peserta) {
    die('Data peserta tidak ditemukan');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Biodata Peserta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />

  <style>
    /* ====== BIODATA PESERTA STYLE (matching dashboard admin) ====== */

body {
    background-color: #fff;
    font-family: "Poppins", sans-serif;
    margin: 0;
}

/* Sidebar mengikuti dashboard admin */
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

/* Sidebar Links */
.sidebar a {
    text-decoration: none;
    color: #222;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 500;
    transition: 0.3s ease;
    margin-bottom: 5px;
}

.sidebar a:hover,
.sidebar a.active {
    background-color: #e6dccb;
      color: black;
      font-weight: 600;
}

/* ------------------------------------------------------------ */
/* MAIN CONTENT */
.main-content {
    margin-left: 260px;
    padding: 35px 45px;
}

/* Logout Link */
.main-content a {
    text-decoration: none;
}


/* ------------------------------------------------------------ */
/* CARD */
.card {
    border: none;
    border-radius: 14px;
    margin-bottom: 20px;
    background-color: #fff;
}

/* Shadow mengikuti dashboard admin */
.shadow-sm {
    box-shadow: 0 2px 6px rgba(0,0,0,0.10);
}

.card h4 {
    font-weight: 700;
    margin-bottom: 20px;
}

/* Biodata Table */
.table th {
    width: 35%;
    color: #333;
    font-weight: 600;
}

.table td {
    color: #444;
    font-size: 15px;
}

/* Profile Icon Circle */
.profile-circle {
    width: 150px;
    height: 150px;
    background: #f9f9f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Badge status */
.badge {
    font-size: 13px;
    border-radius: 6px;
}

/* Calendar spacing */
#calendar {
    margin-top: 10px;
}

/* Responsive (mobile) */
@media (max-width: 768px) {
    .sidebar {
        display: none;
    }
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}


/* ============================
   STYLING HALAMAN JADWAL
   ============================ */

/* Container Card Jadwal */


.sidebar {
    width: 260px;
    background-color: #f2e9dc;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* <- ini membuat logout selalu di bawah */
    padding: 40px 25px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
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
   RESPONSIVE BIODATA
   SAMA DENGAN DASHBOARD ADMIN
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
  background: rgba(0,0,0,0.3);
  z-index: 1500;
}

/* MOBILE MODE */
@media (max-width: 768px) {

  body {
    overflow-x: hidden;
  }

  .sidebar.active ~ .hamburger {
    display: none;
  }

  /* hamburger muncul */
  .hamburger {
    display: block;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 3000;
  }

  /* SIDEBAR SLIDE (BUKAN HILANG) */
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    height: 100vh;
    background-color: #f2e9dc;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 2000;
    display: flex;
  }

  .sidebar.active {
    transform: translateX(0);
  }

  /* overlay */
  .overlay.active {
    display: block;
  }

  /* MAIN CONTENT */
  .main-content {
    margin-left: 0;
    padding: 20px 15px;
  }

  /* CARD */
  .card {
    padding: 20px !important;
  }

  /* PROFILE */
  .profile-circle {
    width: 110px;
    height: 110px;
    margin: 0 auto 15px;
  }

  .profile-circle i {
    font-size: 80px !important;
  }

  /* TABLE */
  .table th,
  .table td {
    font-size: 14px;
  }

  /* CALENDAR */
  #calendar {
    width: 100%;
    overflow-x: auto;
  }
}

  </style>
</head>

<body>
<button class="hamburger" id="hamburgerBtn">☰</button>
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="menu-links">
        <h5>Pengelolaan Rapat</h5>
        <h4>Selamat Datang <?= htmlspecialchars($peserta_name) ?>!</h4>

        <a href="biodata.php" class="active">
            <i class="bi bi-house-door"></i> Home
        </a>
        <a href="undangan.php">
            <i class="bi bi-bookmark"></i> Undangan
        </a>
        <a href="jadwal.php">
            <i class="bi bi-calendar"></i> Jadwal
        </a>
        <a href="notulensi.php">
            <i class="bi bi-file-earmark-text"></i> Notulensi
        </a>
    </div>

    <div class="logout-box">
        <a href="../logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <div class="card shadow-sm p-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <div class="profile-circle">
                    <i class="bi bi-person-circle" style="font-size:120px"></i>
                </div>
            </div>

            <div class="col-md-9">
                <h4>Biodata Peserta</h4>
                <table class="table table-borderless">
                    <tr><th>Nama</th><td>: <?= htmlspecialchars($peserta['name']) ?></td></tr>
                    <tr><th>Email</th><td>: <?= htmlspecialchars($peserta['p_email']) ?></td></tr>
                    <tr><th>No. HP</th><td>: <?= htmlspecialchars($peserta['phone'] ?? '-') ?></td></tr>
                    <tr><th>Instansi</th><td>: <?= htmlspecialchars($peserta['department'] ?? '-') ?></td></tr>
                </table>
                <span class="badge bg-success">Status: Aktif</span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4 mt-4">
        <h4>Jadwal Rapat</h4>
        <div id="calendar"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        height: 650,
        events: 'get_events.php'
    }).render();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.getElementById('hamburgerBtn');
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('overlay');

  if (!hamburger || !sidebar || !overlay) return;

  hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
      hamburger.style.display = 'none';
      document.body.style.overflow = 'hidden';
    } else {
      hamburger.style.display = 'block';
      document.body.style.overflow = 'auto';
    }
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    hamburger.style.display = 'block';
    document.body.style.overflow = 'auto';
  });
});
</script>
        
</body>
</html>
