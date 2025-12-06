<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

// Ambil data user + biodata peserta dari tabel participant
$user_id = $_SESSION['user_id'];

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
    die("Data peserta tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Peserta</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />

  <style>
    body { font-family: 'Poppins', sans-serif; background: white; }
    .sidebar { width: 230px; background: #f6ebdf; min-height: 100vh; position: fixed; left: 0; top: 0; padding: 25px 20px; }
    .sidebar a { display: flex; align-items: center; padding: 10px; border-radius: 8px; text-decoration: none; color: black; margin-bottom: 10px; }
    .sidebar a.active { background: #00eaff; }
    .main-content { margin-left: 230px; padding: 30px 50px; }
  </style>
</head>

<body>

<div class="sidebar">
    <h5>Pengelolaan Rapat</h5>
    <h4 class="fw-bold mb-4">Peserta</h4>
    <a href="biodata.php" class="active"><i class="bi bi-house-door"></i>Home</a>
    <a href="undangan.php"><i class="bi bi-bookmark"></i>Undangan</a>
    <a href="jadwal.php"><i class="bi bi-calendar"></i>Jadwal</a>
    <a href="notulensi.php"><i class="bi bi-file-earmark-text"></i>Notulensi</a>
</div>

<div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="../logout.php" class="text-dark fw-semibold">Logout <i class="bi bi-box-arrow-right"></i></a>
    </div>

    <div class="card shadow-sm p-4">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center" style="width:150px; height:150px;">
                    <i class="bi bi-person-circle text-dark" style="font-size: 120px;"></i>
                </div>
            </div>

            <div class="col-md-9">
                <h4 class="fw-bold mb-3 text-black">Biodata Peserta</h4>
                <table class="table table-borderless">
                    <tr><th class="w-25">Nama</th><td>: <?= htmlspecialchars($peserta['name']); ?></td></tr>
                    <tr><th>Email</th><td>: <?= htmlspecialchars($peserta['p_email']); ?></td></tr>
                    <tr><th>No. HP</th><td>: <?= htmlspecialchars($peserta['phone'] ?? '-'); ?></td></tr>
                    <tr><th>Instansi / Divisi</th><td>: <?= htmlspecialchars($peserta['department'] ?? '-'); ?></td></tr>
                </table>
                <span class="badge bg-success px-3 py-2">Status: Aktif</span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4 mt-4">
        <h4 class="fw-bold mb-3 text-black">Jadwal Rapat</h4>
        <div id="calendar"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        events: 'get_events.php' // Pastikan file ini ada dan mengembalikan data JSON
    });

    calendar.render();
});
</script>

</body>
</html>
