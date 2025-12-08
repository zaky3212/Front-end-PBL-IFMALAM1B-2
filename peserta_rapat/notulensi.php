<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';

$peserta_name = $_SESSION['username'];
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
<title>Notulensi Rapat - Peserta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- CSS khusus notulensi peserta -->
<link rel="stylesheet" href="../assets/style_notulensi_peserta.css">
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
        <form method="GET" class="d-flex">
            <input type="text" name="search" placeholder="Search..." class="form-control me-2" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        </form>
        
    </div>

    <div class="card shadow-sm p-4 notulensi-card">
        <h4 class="fw-bold mb-3">Daftar Notulensi</h4>

        <table class="table table-hover align-middle">
            <thead class="table-light">
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
                        <a href="view_notulen.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Lihat</a>
                        <a href="download_notulen.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Download</a>
                    </td>
                </tr>
            <?php }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada data</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
