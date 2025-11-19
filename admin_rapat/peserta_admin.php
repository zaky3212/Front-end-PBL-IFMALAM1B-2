<?php 
include '../koneksi.php';

// Fungsi untuk mendapatkan data peserta dari database
function getParticipants($koneksi) {
    $sql = "SELECT * FROM participant ORDER BY id DESC";
    $result = mysqli_query($koneksi, $sql);
    
    $participants = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $participants[] = $row;
        }
    }
    return $participants;
}

// Fungsi untuk menambahkan peserta ke database
function addParticipant($koneksi, $data) {
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $department = mysqli_real_escape_string($koneksi, $data['department']);
    $position = mysqli_real_escape_string($koneksi, $data['position']);
    $phone = mysqli_real_escape_string($koneksi, $data['phone']);
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO participant (name, email, department, position, phone, created_at) 
            VALUES ('$name', '$email', '$department', '$position', '$phone', '$created_at')";
    
    return mysqli_query($koneksi, $sql);
}

// Fungsi untuk mengupdate peserta di database
function updateParticipant($koneksi, $id, $data) {
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $department = mysqli_real_escape_string($koneksi, $data['department']);
    $position = mysqli_real_escape_string($koneksi, $data['position']);
    $phone = mysqli_real_escape_string($koneksi, $data['phone']);
    $created_at = date('Y-m-d H:i:s');

    $sql = "UPDATE participant SET 
            name = '$name',
            email = '$email',
            department = '$department',
            position = '$position',
            phone = '$phone',
            created_at = '$created_at'
            WHERE id = $id";
    
    return mysqli_query($koneksi, $sql);
}

// Fungsi untuk menghapus peserta dari database
function deleteParticipant($koneksi, $id) {
    $sql = "DELETE FROM participant WHERE id = $id";
    return mysqli_query($koneksi, $sql);
}

// Proses form tambah/edit peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'department' => $_POST['department'],
            'position' => $_POST['position'],
            'phone' => $_POST['phone']
        ];
        
        if ($action === 'tambah') {
            if (addParticipant($koneksi, $data)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
                exit();
            }
        } elseif ($action === 'edit' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            if (updateParticipant($koneksi, $id, $data)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=2");
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=2");
                exit();
            }
        }
    }
}

// Proses hapus peserta
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (deleteParticipant($koneksi, $id)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=3");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=3");
        exit();
    }
}

// Ambil data peserta dari database
$participants = getParticipants($koneksi);

// Hitung statistik
$totalPeserta = count($participants);

// Pesan sukses/error
$success_msg = "";
$error_msg = "";

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $success_msg = "Peserta berhasil ditambahkan!"; break;
        case '2': $success_msg = "Peserta berhasil diperbarui!"; break;
        case '3': $success_msg = "Peserta berhasil dihapus!"; break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1': $error_msg = "Gagal menambahkan peserta!"; break;
        case '2': $error_msg = "Gagal memperbarui peserta!"; break;
        case '3': $error_msg = "Gagal menghapus peserta!"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peserta Rapat - Admin</title>
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

    .sidebar .menu {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .menu a {
      text-decoration: none;
      color: #222;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 15px;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .menu a:hover,
    .menu a.active {
      background-color: #00f7ff;
      color: #000;
      font-weight: 600;
    }

    .menu i {
      width: 20px;
      text-align: center;
    }

    /* Main Content */
    .main {
      flex: 1;
      background-color: #fff;
      padding: 25px 40px;
      overflow-y: auto;
    }

    /* Topbar */
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .topbar h1 {
      font-size: 32px;
      font-weight: 700;
    }

    .topbar .right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      padding: 8px 35px 8px 15px;
      border: 1px solid #ccc;
      border-radius: 10px;
      outline: none;
      background-color: #f9f9f9;
      font-size: 14px;
    }

    .search-box i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
    }

    .bell {
      font-size: 18px;
      color: #555;
      cursor: pointer;
    }

    /* Peserta Content */
    .peserta-container {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .peserta-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .peserta-header h2 {
      font-size: 24px;
      font-weight: 600;
    }

    .btn-tambah {
      background-color: #000;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .btn-tambah:hover {
      background-color: #333;
    }

    /* Filter dan Tools Section */
    .tools-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .filter-group {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .filter-select {
      padding: 8px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #fff;
      font-size: 14px;
      cursor: pointer;
    }

    .action-tools {
      display: flex;
      gap: 10px;
    }

    .btn-tool {
      background-color: #f2e9dc;
      border: none;
      padding: 8px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s;
    }

    .btn-tool:hover {
      background-color: #e0d8cc;
    }

    /* Peserta Table */
    .peserta-table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    .peserta-table th {
      background-color: #f2e9dc;
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #333;
    }

    .peserta-table td {
      padding: 15px;
      border-bottom: 1px solid #eee;
    }

    .peserta-table tr:hover {
      background-color: #f9f9f9;
    }

    .peserta-table tr:last-child td {
      border-bottom: none;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-action {
      padding: 6px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s;
    }

    .btn-edit {
      background-color: #fff8e5;
      color: #f57c00;
    }

    .btn-edit:hover {
      background-color: #ffeebc;
    }

    .btn-delete {
      background-color: #ffeaea;
      color: #d32f2f;
    }

    .btn-delete:hover {
      background-color: #ffd1d1;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      padding: 20px 25px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      font-size: 22px;
      font-weight: 600;
      position: relative;
    }

    .modal-header h2::before {
      content: "";
      position: absolute;
      left: -15px;
      top: 50%;
      transform: translateY(-50%);
      width: 5px;
      height: 25px;
      background-color: #f4ce14;
      border-radius: 3px;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #777;
      transition: color 0.3s;
    }

    .close-modal:hover {
      color: #333;
    }

    .modal-body {
      padding: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
    }

    .form-control {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      transition: border 0.3s;
    }

    .form-control:focus {
      outline: none;
      border-color: #00f7ff;
    }

    .form-row {
      display: flex;
      gap: 15px;
    }

    .form-row .form-group {
      flex: 1;
    }

    textarea.form-control {
      resize: vertical;
      min-height: 80px;
    }

    .modal-footer {
      padding: 20px 25px;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-secondary {
      background-color: #f5f5f5;
      color: #333;
    }

    .btn-secondary:hover {
      background-color: #e0e0e0;
    }

    .btn-primary {
      background-color: #000;
      color: white;
    }

    .btn-primary:hover {
      background-color: #333;
    }

    /* Calendar Section */
    .calendar-section {
      background-color: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .calendar-header h3 {
      font-size: 18px;
      font-weight: 600;
    }

    .calendar-nav {
      display: flex;
      gap: 10px;
    }

    .calendar-nav button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 16px;
      color: #555;
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
    }

    .calendar-day {
      text-align: center;
      font-weight: 600;
      color: #333;
      padding: 8px;
      font-size: 12px;
    }

    .calendar-date {
      text-align: center;
      padding: 8px;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .calendar-date:hover {
      background-color: #eaf4ff;
    }

    .calendar-date.today {
      background-color: #f4ce14;
      color: white;
      font-weight: 600;
    }

    .calendar-date.has-event {
      background-color: #e6f7e6;
      font-weight: 500;
    }

    .calendar-date.other-month {
      color: #ccc;
    }

    /* Stats Cards */
    .stats-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 25px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: center;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 700;
      color: #000;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 14px;
      color: #666;
    }

    /* Alert Messages */
    .alert {
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 500;
    }

    .alert-success {
      background-color: #e6f7e6;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }

    .alert-error {
      background-color: #ffeaea;
      color: #d32f2f;
      border: 1px solid #ffcdd2;
    }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .main { padding: 20px; }
      .peserta-header { flex-direction: column; align-items: flex-start; gap: 15px; }
      .tools-section { flex-direction: column; align-items: flex-start; }
      .filter-group { flex-direction: column; width: 100%; }
      .peserta-table { font-size: 14px; }
      .peserta-table th, .peserta-table td { padding: 10px; }
      .form-row { flex-direction: column; }
      .stats-cards { grid-template-columns: 1fr; }
      .calendar-grid { gap: 2px; }
      .calendar-date { padding: 5px; font-size: 12px; }
    }
  </style>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Pengelolaan Rapat</h2>
    <div class="menu">
      <a href="dashboard_admin.php"><i class="fas fa-home"></i> Home</a>
      <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
      <a href="peserta_admin.php" class="active"><i class="fas fa-user-graduate"></i> Peserta</a>
      <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="topbar">
      <h1>Peserta Rapat</h1>
      <div class="right">
        <div class="search-box">
          <input type="text" placeholder="Search peserta..." id="searchInput">
          <i class="fas fa-search"></i>
        </div>
        <i class="fas fa-bell bell"></i>
      </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success_msg)): ?>
      <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_msg)): ?>
      <div class="alert alert-error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="stats-cards">
      <div class="stat-card">
        <div class="stat-number" id="totalPeserta"><?php echo $totalPeserta; ?></div>
        <div class="stat-label">Total Peserta</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="totalRapat">0</div>
        <div class="stat-label">Total Rapat</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="kehadiranRata">0%</div>
        <div class="stat-label">Rata-rata Kehadiran</div>
      </div>
    </div>

    <!-- Calendar Section -->
    <div class="calendar-section">
      <div class="calendar-header">
        <h3 id="calendarMonth">Desember 2023</h3>
        <div class="calendar-nav">
          <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
          <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
      <div class="calendar-grid">
        <div class="calendar-day">Sen</div>
        <div class="calendar-day">Sel</div>
        <div class="calendar-day">Rab</div>
        <div class="calendar-day">Kam</div>
        <div class="calendar-day">Jum</div>
        <div class="calendar-day">Sab</div>
        <div class="calendar-day">Min</div>
        <!-- Calendar dates will be populated by JavaScript -->
      </div>
    </div>

    <!-- Peserta Content -->
    <div class="peserta-container">
      <div class="peserta-header">
        <h2>Daftar Peserta</h2>
        <button class="btn-tambah" id="btnTambahPeserta">
          <i class="fas fa-plus"></i> Tambah Peserta
        </button>
      </div>

      <!-- Tools Section -->
      <div class="tools-section">
        <div class="filter-group">
          <select class="filter-select" id="filterDepartemen">
            <option value="all">Semua Departemen</option>
            <option value="it">IT</option>
            <option value="hrd">HRD</option>
            <option value="marketing">Marketing</option>
            <option value="keuangan">Keuangan</option>
            <option value="operasional">Operasional</option>
          </select>
        </div>
        <div class="action-tools">
          <button class="btn-tool" id="btnExport">
            <i class="fas fa-download"></i> Export
          </button>
          <button class="btn-tool" id="btnPrint">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
      </div>

      <!-- Peserta Table -->
      <table class="peserta-table">
        <thead>
          <tr>
            <th>Nama Peserta</th>
            <th>Email</th>
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Telepon</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <?php if (count($participants) > 0): ?>
            <?php foreach ($participants as $participant): ?>
              <tr>
                <td><?php echo htmlspecialchars($participant['name']); ?></td>
                <td><?php echo htmlspecialchars($participant['email']); ?></td>
                <td><?php echo htmlspecialchars($participant['department']); ?></td>
                <td><?php echo htmlspecialchars($participant['position']); ?></td>
                <td><?php echo htmlspecialchars($participant['phone']); ?></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-edit" 
                            data-id="<?php echo $participant['id']; ?>"
                            data-name="<?php echo htmlspecialchars($participant['name']); ?>"
                            data-email="<?php echo htmlspecialchars($participant['email']); ?>"
                            data-department="<?php echo htmlspecialchars($participant['department']); ?>"
                            data-position="<?php echo htmlspecialchars($participant['position']); ?>"
                            data-phone="<?php echo htmlspecialchars($participant['phone']); ?>">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="?hapus=<?php echo $participant['id']; ?>" class="btn-action btn-delete" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                      <i class="fas fa-trash"></i> Hapus
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center;">Tidak ada data peserta</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah/Edit Peserta -->
  <div class="modal" id="modalPeserta">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Tambah Peserta Baru</h2>
        <button class="close-modal">&times;</button>
      </div>
      <form id="formPeserta" method="POST" action="">
        <input type="hidden" id="action" name="action" value="tambah">
        <input type="hidden" id="participantId" name="id" value="">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Nama Lengkap *</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="department">Departemen *</label>
              <select id="department" name="department" class="form-control" required>
                <option value="">Pilih Departemen</option>
                <option value="it">IT</option>
                <option value="hrd">HRD</option>
                <option value="marketing">Marketing</option>
                <option value="keuangan">Keuangan</option>
                <option value="operasional">Operasional</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label for="position">Jabatan *</label>
              <input type="text" id="position" name="position" class="form-control" placeholder="Masukkan jabatan" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="phone">Nomor Telepon</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="Masukkan nomor telepon">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Peserta</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Elemen DOM
    const modalPeserta = document.getElementById('modalPeserta');
    const btnTambahPeserta = document.getElementById('btnTambahPeserta');
    const closeModal = document.querySelector('.close-modal');
    const btnBatal = document.getElementById('btnBatal');
    const formPeserta = document.getElementById('formPeserta');
    const modalTitle = document.getElementById('modalTitle');
    const actionInput = document.getElementById('action');
    const participantIdInput = document.getElementById('participantId');
    const btnSubmit = document.getElementById('btnSubmit');

    // Calendar elements
    const calendarMonth = document.getElementById('calendarMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const calendarGrid = document.querySelector('.calendar-grid');

    let currentDate = new Date();

    // Fungsi untuk membuka modal tambah
    btnTambahPeserta.addEventListener('click', function() {
      actionInput.value = "tambah";
      modalTitle.textContent = "Tambah Peserta Baru";
      btnSubmit.textContent = "Simpan Peserta";
      formPeserta.reset();
      modalPeserta.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });

    // Fungsi untuk membuka modal edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        const data = {
          id: this.getAttribute('data-id'),
          name: this.getAttribute('data-name'),
          email: this.getAttribute('data-email'),
          department: this.getAttribute('data-department'),
          position: this.getAttribute('data-position'),
          phone: this.getAttribute('data-phone')
        };

        actionInput.value = "edit";
        modalTitle.textContent = "Edit Peserta";
        btnSubmit.textContent = "Update Peserta";
        
        participantIdInput.value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('email').value = data.email;
        document.getElementById('department').value = data.department;
        document.getElementById('position').value = data.position;
        document.getElementById('phone').value = data.phone;
        
        modalPeserta.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      });
    });

    // Fungsi untuk menutup modal
    function tutupModal() {
      modalPeserta.style.display = 'none';
      document.body.style.overflow = 'auto';
      formPeserta.reset();
    }

    closeModal.addEventListener('click', tutupModal);
    btnBatal.addEventListener('click', tutupModal);

    // Tutup modal ketika klik di luar konten modal
    window.addEventListener('click', function(event) {
      if (event.target === modalPeserta) {
        tutupModal();
      }
    });

    // Calendar functions
    function renderCalendar() {
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      
      // Update calendar header
      const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                         "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
      calendarMonth.textContent = `${monthNames[month]} ${year}`;
      
      // Clear existing dates (keep day headers)
      const existingDates = document.querySelectorAll('.calendar-date');
      existingDates.forEach(date => date.remove());
      
      // Get first day of month and number of days
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const daysInMonth = lastDay.getDate();
      
      // Get day of week for first day (0 = Sunday, 1 = Monday, etc.)
      let firstDayOfWeek = firstDay.getDay();
      // Convert to Monday-based week (0 = Monday, 6 = Sunday)
      firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;
      
      // Add empty cells for days before the first day of month
      for (let i = 0; i < firstDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'calendar-date other-month';
        emptyCell.textContent = '';
        calendarGrid.appendChild(emptyCell);
      }
      
      // Add cells for each day of the month
      const today = new Date();
      for (let day = 1; day <= daysInMonth; day++) {
        const dateCell = document.createElement('div');
        dateCell.className = 'calendar-date';
        dateCell.textContent = day;
        
        // Check if today
        if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
          dateCell.classList.add('today');
        }
        
        // Check if has event (random for demo)
        if (Math.random() > 0.7) {
          dateCell.classList.add('has-event');
        }
        
        calendarGrid.appendChild(dateCell);
      }
    }

    // Calendar navigation
    prevMonthBtn.addEventListener('click', function() {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar();
    });

    nextMonthBtn.addEventListener('click', function() {
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar();
    });

    // Fungsi untuk menangani pencarian
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.peserta-table tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Fungsi untuk filter berdasarkan departemen
    document.getElementById('filterDepartemen').addEventListener('change', function(e) {
      const departemen = e.target.value;
      const rows = document.querySelectorAll('.peserta-table tbody tr');
      
      rows.forEach(row => {
        const departemenCell = row.cells[2]; // Kolom departemen adalah index 2
        if (departemen === 'all' || departemenCell.textContent.toLowerCase() === departemen) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Export functionality
    document.getElementById('btnExport').addEventListener('click', function() {
      alert('Fitur export data peserta akan dijalankan');
      // Di sini bisa implementasi export ke Excel/CSV
    });

    // Print functionality
    document.getElementById('btnPrint').addEventListener('click', function() {
      window.print();
    });

    // Inisialisasi
    renderCalendar();
  </script>
</body>
</html>