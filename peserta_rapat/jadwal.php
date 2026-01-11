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
            $sql = "
            SELECT *,
            CASE
              WHEN NOW() > CONCAT(dates, ' ', end_time) THEN 'Selesai'
              WHEN NOW() BETWEEN CONCAT(dates, ' ', start_time) AND CONCAT(dates, ' ', end_time) THEN 'Berlangsung'
              ELSE 'Mendatang'
            END AS status_rapat
            FROM meetings
            WHERE
              title LIKE '%$search%' OR
              leader LIKE '%$search%' OR
              locations LIKE '%$search%' OR
              start_time LIKE '%$search%' OR
              end_time LIKE '%$search%' OR
              dates LIKE '%$search%' OR
              (
                CASE
                  WHEN NOW() > CONCAT(dates, ' ', end_time) THEN 'Selesai'
                  WHEN NOW() BETWEEN CONCAT(dates, ' ', start_time) AND CONCAT(dates, ' ', end_time) THEN 'Berlangsung'
                  ELSE 'Mendatang'
                END
              ) LIKE '%$search%'
            ORDER BY dates DESC
            ";

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


                <style>
                    /* ====== Global ====== */
                    body {
                        background-color: #fff;
                        font-family: "Poppins", sans-serif;
                        margin: 0;
                    }

                    /* ===== Sidebar ===== */
                    .sidebar {
                        width: 260px;
                        background-color: #f2e9dc;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
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

                    /* ===== Main Content ===== */
                    .main-content {
                        margin-left: 260px;
                        padding: 35px 45px;
                    }

                    /* Logout */


                    /* ===== Card Jadwal ===== */
                    .jadwal-card {
                        border-radius: 14px;
                        background-color: #fff;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.10);
                        padding: 30px;
                    }

                    .jadwal-title {
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

                    /* Status badge */
                    .status {
                        padding: 5px 10px;
                        border-radius: 6px;
                        font-weight: 500;
                        font-size: 13px;
                        color: #fff;
                    }

                    .status.selesai {
                        background-color: #6c757d;
                    }

                    .status.berlangsung {
                        background-color: #198754;
                    }

                    .status.mendatang {
                        background-color: #0d6efd;
                    }

                    /* Tombol detail */
                    .btn-primary {
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
                        gap: 10px;
                        padding: 12px 16px;
                        width: 100%;
                        background-color: #ff4d4f;
                        /* WARNA TETAP */
                        color: #fff;
                        font-weight: 600;
                        border-radius: 12px;
                        text-decoration: none;
                        letter-spacing: 0.3px;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }

                    .logout-btn i {
                        font-size: 18px;
                    }

                    .logout-btn:hover {
                        background-color: #ff4d4f;
                        /* WARNA TETAP */
                        transform: translateY(-2px);
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                    }

                    /* ===============================
            RESPONSIVE JADWAL PESERTA
            (SAMA DENGAN BIODATA)
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
                        background: rgba(0, 0, 0, 0.3);
                        z-index: 1500;
                    }

                    /* Sembunyikan hamburger saat sidebar aktif */
                    .sidebar.active~.hamburger {
                        display: none;
                    }


                    /* MOBILE MODE */
                    @media (max-width: 768px) {

                        body {
                            overflow-x: hidden;
                        }

                        /* HAMBURGER */
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
                            z-index: 2000;
                        }

                        .sidebar.active {
                            transform: translateX(0);
                        }

                        /* OVERLAY */
                        .overlay.active {
                            display: block;
                        }

                        /* MAIN */
                        .main-content {
                            margin-left: 0;
                            padding: 20px 15px;
                        }

                        /* TABLE */
                        .table {
                            display: block;
                            overflow-x: auto;
                            white-space: nowrap;
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
                        <h4 class="fw-bold mb-4">Selamat Datang <?= htmlspecialchars($peserta_name) ?>!</h4>
                        <a href="biodata.php"><i class="bi bi-house-door"></i> Home</a>
                        <a href="undangan.php"><i class="bi bi-bookmark"></i> Undangan</a>
                        <a href="jadwal.php"><i class="bi bi-calendar"></i> Jadwal</a>
                        <a href="notulensi.php"><i class="bi bi-file-earmark-text"></i> Notulensi</a>
                    </div>


                    <!-- Logout Box -->
                    <div class="logout-box">
                        <a href="../logout.php" class="logout-btn">
                            Keluar
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
                                    <th>Pemimpin</th>
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
                                        $status = $row['status_rapat'];
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
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada jadwal rapat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const hamburger = document.getElementById('hamburgerBtn');
                        const sidebar = document.querySelector('.sidebar');
                        const overlay = document.getElementById('overlay');

                        if (!hamburger || !sidebar || !overlay) return;

                        hamburger.addEventListener('click', () => {
                            sidebar.classList.add('active');
                            overlay.classList.add('active');
                            hamburger.style.display = 'none';
                            document.body.style.overflow = 'hidden';
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