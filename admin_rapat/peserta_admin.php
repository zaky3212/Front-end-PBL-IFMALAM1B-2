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

// Fungsi untuk menghapus peserta dari database
function deleteParticipant($koneksi, $id) {
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
  <link rel="stylesheet" href="../assets/style_peserta_admin.css">
  <title>Peserta Rapat - Admin</title>
 
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
      <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
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
  </script>
</body>
</html>