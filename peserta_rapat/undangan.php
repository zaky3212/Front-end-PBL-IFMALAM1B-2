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

</head>
<body>

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
</body>
</html>
