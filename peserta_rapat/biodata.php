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
  <link rel="stylesheet" href="../assets/style_biodata.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
</head>

<body>

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

</body>
</html>
