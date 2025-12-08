<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$peserta_name = $_SESSION['username'];

// Ambil kata pencarian dari GET dan escape untuk keamanan
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Query data rapat dengan filter ruang
$sql = "SELECT * FROM meetings 
        WHERE locations LIKE '%$search%' 
        ORDER BY dates DESC";

$result = mysqli_query($koneksi, $sql);
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Function status rapat
function getStatus($dates, $start_time, $end_time)
{
    $now = time();
    $start = strtotime($dates . " " . $start_time);
    $end = strtotime($dates . " " . $end_time);

    if ($now > $end) return "Selesai";
    if ($now >= $start && $now <= $end) return "Berlangsung";
    return "Mendatang";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jadwal Peserta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- CSS khusus jadwal peserta -->
<link rel="stylesheet" href="../assets/style_jadwal_peserta.css">

</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="menu-links">
        <h5>Pengelolaan Rapat</h5>
        <h4 class="fw-bold mb-4">Selamat Datang <?= htmlspecialchars($peserta_name) ?>!</h4>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" class="d-flex search-bar">
            <input type="text" name="search" placeholder="Cari ruang..." class="form-control me-2" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        </form>
    </div>

    <div class="card shadow-sm p-4 jadwal-card">
        <h4 class="fw-bold mb-3 text-black jadwal-title">Jadwal Rapat</h4>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul Rapat</th>
                    <th>Leader</th>
                    <th>Ruang</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)):
                    $status = getStatus($row['dates'], $row['start_time'], $row['end_time']);
                    $badgeClass = strtolower($status); ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['leader']); ?></td>
                        <td><?= htmlspecialchars($row['locations']); ?></td>
                        <td><?= htmlspecialchars($row['start_time']); ?></td>
                        <td><?= htmlspecialchars($row['end_time']); ?></td>
                        <td><?= htmlspecialchars($row['dates']); ?></td>
                        <td>
                            <span class="status <?= $badgeClass ?>"><?= $status ?></span>
                        </td>
                        <td>
                            <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">Belum ada jadwal rapat.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
