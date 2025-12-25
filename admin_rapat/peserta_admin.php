<?php
session_start();
include '../koneksi.php';

$admin_name = $_SESSION['username'];

// Fungsi untuk mendapatkan data peserta dari database
function getParticipants($koneksi)
{
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

// Fungsi untuk menghapus peserta dari database
function deleteParticipant($koneksi, $id)
{
  $sql = "DELETE FROM participant WHERE id = $id";
  return mysqli_query($koneksi, $sql);
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
    case '1':
      $success_msg = "Peserta berhasil ditambahkan!";
      break;
    case '2':
      $success_msg = "Peserta berhasil diperbarui!";
      break;
    case '3':
      $success_msg = "Peserta berhasil dihapus!";
      break;
  }
}

if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case '1':
      $error_msg = "Gagal menambahkan peserta!";
      break;
    case '2':
      $error_msg = "Gagal memperbarui peserta!";
      break;
    case '3':
      $error_msg = "Gagal menghapus peserta!";
      break;
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style_peserta_admin.css">
  <title>Peserta Rapat - Admin</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    /* Tombol Edit & Hapus minimal styling */
    /* ===============================
   SIDEBAR FINAL
================================ */

    .sidebar {
      width: 250px;
      background-color: #f2e9dc;
      /* cream utama */
      height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
    }

    /* Judul sidebar */
    .sidebar h2 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 40px;
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

    /* Menu */
    .menu {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .menu a {
      position: relative;
      text-decoration: none;
      color: #222;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 15px;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    /* HOVER & ACTIVE */
    .menu a:hover,
    .menu a.active {
      background-color: #e6dccb;
      /* cream lebih gelap */
      color: #000;
      font-weight: 600;
    }


    .menu i {
      width: 20px;
      text-align: center;
    }

    /* Logout di bawah */
    .logout-box {
      margin-top: auto;
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
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: #ff4d4d;
      color: #fff;
      border-color: #ff4d4d;
      transform: translateY(-2px);
      box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.25);
    }

    /* ===============================
   HAMBURGER & OVERLAY
================================ */
    .hamburger {
      display: none;
      font-size: 26px;
      background: none;
      border: none;
      cursor: pointer;
    }

    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.3);
      z-index: 1500;
    }

    /* ===============================
   MOBILE (SAMA DENGAN DASHBOARD)
================================ */
    @media (max-width: 768px) {

      body {
        overflow-x: hidden;
      }

      .hamburger {
        display: block;
      }

      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
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

      .main {
        width: 100%;
        padding: 20px;
      }

      .overlay.active {
        display: block;
      }
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
      <a href="peserta_admin.php" class="active"><i class="fas fa-user-graduate"></i> Peserta</a>
      <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
      <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
      <a href="tambah_akun.php"><i class="fas fa-file-alt"></i> Tambah Akun</a>
    </div>
    <div class="logout-box">
      <a href="../logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>
  <div class="overlay" id="overlay"></div>


  <div class="main">
    <div class="topbar">
      <button class="hamburger" id="hamburgerBtn">☰</button>
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

    <div class="peserta-container">
      <div class="peserta-header">
        <h2>Daftar Peserta</h2>
        <button class="btn-tambah" id="btnTambahPeserta">
          <i class="fas fa-plus"></i> Tambah Peserta
        </button>
      </div>

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
          <a href="export_peserta_pdf.php" class="btn-tool">
            <i class="fas fa-file-pdf"></i> Export PDF
          </a>

        </div>
      </div>

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
                <td><?= htmlspecialchars($participant['name']); ?></td>
                <td><?= htmlspecialchars($participant['email']); ?></td>
                <td><?= htmlspecialchars($participant['department']); ?></td>
                <td><?= htmlspecialchars($participant['position']); ?></td>
                <td><?= htmlspecialchars($participant['phone']); ?></td>
                <td>
                  <a href="#" class="btn-action btn-edit"
                    data-id="<?= $participant['id']; ?>"
                    data-name="<?= htmlspecialchars($participant['name']); ?>"
                    data-email="<?= htmlspecialchars($participant['email']); ?>"
                    data-department="<?= htmlspecialchars($participant['department']); ?>"
                    data-position="<?= htmlspecialchars($participant['position']); ?>"
                    data-phone="<?= htmlspecialchars($participant['phone']); ?>">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="proses_hapus_peserta.php?id=<?= $participant['id']; ?>"
                    class="btn-action btn-delete"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                    <i class="fas fa-trash"></i> Hapus
                  </a>
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

  <!-- Modal Tambah/Edit Peserta (sama seperti sebelumnya) -->
  <div class="modal" id="modalPeserta">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Tambah Peserta Baru</h2>
        <button class="close-modal">&times;</button>
      </div>
      <form id="formPeserta" method="POST" action="proses_tambah_peserta.php">
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
              <input type="text" id="department" name="department" class="form-control" placeholder="Masukkan departemen" required>
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
    const modalPeserta = document.getElementById('modalPeserta');
    const btnTambahPeserta = document.getElementById('btnTambahPeserta');
    const closeModal = document.querySelector('.close-modal');
    const btnBatal = document.getElementById('btnBatal');
    const formPeserta = document.getElementById('formPeserta');
    const modalTitle = document.getElementById('modalTitle');
    const actionInput = document.getElementById('action');
    const participantIdInput = document.getElementById('participantId');
    const btnSubmit = document.getElementById('btnSubmit');

    // Modal Tambah
    btnTambahPeserta.addEventListener('click', () => {
      actionInput.value = "tambah";
      formPeserta.action = "proses_tambah_peserta.php";
      modalTitle.textContent = "Tambah Peserta Baru";
      btnSubmit.textContent = "Simpan Peserta";
      formPeserta.reset();
      modalPeserta.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });

    // Modal Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        actionInput.value = "edit";
        formPeserta.action = "proses_edit_peserta.php";
        modalTitle.textContent = "Edit Peserta";
        btnSubmit.textContent = "Update Peserta";

        participantIdInput.value = this.dataset.id;
        document.getElementById('name').value = this.dataset.name;
        document.getElementById('email').value = this.dataset.email;
        document.getElementById('department').value = this.dataset.department;
        document.getElementById('position').value = this.dataset.position;
        document.getElementById('phone').value = this.dataset.phone;

        modalPeserta.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      });
    });

    // Tutup modal
    function tutupModal() {
      modalPeserta.style.display = 'none';
      document.body.style.overflow = 'auto';
      formPeserta.reset();
    }

    closeModal.addEventListener('click', tutupModal);
    btnBatal.addEventListener('click', tutupModal);
    window.addEventListener('click', e => {
      if (e.target === modalPeserta) tutupModal();
    });

    // Pencarian
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      document.querySelectorAll('.peserta-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
      });
    });

    // Filter departemen
    document.getElementById('filterDepartemen').addEventListener('change', function(e) {
      const departemen = e.target.value;
      document.querySelectorAll('.peserta-table tbody tr').forEach(row => {
        const departemenCell = row.cells[2];
        row.style.display = (departemen === 'all' || departemenCell.textContent.toLowerCase() === departemen) ? '' : 'none';
      });
    });

    // Export & Print
    document.getElementById('btnExport').addEventListener('click', () => alert('Fitur export data peserta akan dijalankan'));
    document.getElementById('btnPrint').addEventListener('click', () => window.print());
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const hamburgerBtn = document.getElementById('hamburgerBtn');
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.getElementById('overlay');

      if (!hamburgerBtn) return;

      function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }

      function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
      }

      hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.contains('active') ?
          closeSidebar() :
          openSidebar();
      });

      overlay.addEventListener('click', closeSidebar);
    });
  </script>

</body>

</html>