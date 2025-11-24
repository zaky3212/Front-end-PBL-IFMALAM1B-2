<?php include '../koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notulen Rapat - Admin</title>
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

    /* Notulen Content */
    .notulen-container {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .notulen-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notulen-header h2 {
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

    /* Notulen Table */
    .notulen-table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    .notulen-table th {
      background-color: #f2e9dc;
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #333;
    }

    .notulen-table td {
      padding: 15px;
      border-bottom: 1px solid #eee;
    }

    .notulen-table tr:hover {
      background-color: #f9f9f9;
    }

    .notulen-table tr:last-child td {
      border-bottom: none;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .status-selesai {
      background-color: #e6f7e6;
      color: #2e7d32;
    }

    .status-draft {
      background-color: #fff8e5;
      color: #f57c00;
    }

    .status-review {
      background-color: #eaf4ff;
      color: #1976d2;
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

    .btn-view {
      background-color: #eaf4ff;
      color: #1976d2;
    }

    .btn-view:hover {
      background-color: #d1e7ff;
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

    .btn-download {
      background-color: #e6f7e6;
      color: #2e7d32;
    }

    .btn-download:hover {
      background-color: #d4f0d4;
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
      max-width: 800px;
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
      min-height: 120px;
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

    /* View Modal Styles */
    .notulen-view {
      line-height: 1.6;
    }

    .notulen-view .info-group {
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .notulen-view .info-label {
      font-weight: 600;
      color: #333;
      margin-bottom: 5px;
    }

    .notulen-view .info-value {
      color: #666;
    }

    .notulen-view .agenda-section {
      margin: 20px 0;
    }

    .notulen-view .agenda-item {
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .notulen-view .agenda-title {
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }

    .notulen-view .agenda-content {
      color: #666;
    }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .main { padding: 20px; }
      .notulen-header { flex-direction: column; align-items: flex-start; gap: 15px; }
      .tools-section { flex-direction: column; align-items: flex-start; }
      .filter-group { flex-direction: column; width: 100%; }
      .notulen-table { font-size: 14px; }
      .notulen-table th, .notulen-table td { padding: 10px; }
      .form-row { flex-direction: column; }
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
      <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
      <a href="notulen_admin.php" class="active"><i class="fas fa-file-alt"></i> Notulen</a>
      <a href="undangan_admin.php" ><i class="fas fa-file-alt"></i> Undangan</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="topbar">
      <h1>Notulen Rapat</h1>
      <div class="right">
        <div class="search-box">
          <input type="text" placeholder="Search notulen...">
          <i class="fas fa-search"></i>
        </div>
        <i class="fas fa-bell bell"></i>
      </div>
    </div>

    <!-- Notulen Content -->
    <div class="notulen-container">
      <div class="notulen-header">
        <h2>Daftar Notulen</h2>
        <button class="btn-tambah" id="btnTambahNotulen">
          <i class="fas fa-plus"></i> Tambah Notulen
        </button>
      </div>

      <!-- Tools Section -->
      <div class="tools-section">
        <div class="filter-group">
          <select class="filter-select" id="filterStatus">
            <option value="all">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="review">Review</option>
            <option value="selesai">Selesai</option>
          </select>
          <select class="filter-select" id="filterRapat">
            <option value="all">Semua Rapat</option>
            <option value="rapat-koordinasi">Rapat Koordinasi</option>
            <option value="rapat-evaluasi">Rapat Evaluasi</option>
            <option value="rapat-perencanaan">Rapat Perencanaan</option>
          </select>
          <input type="date" class="filter-select" id="filterTanggal">
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

      <!-- Notulen Table -->
      <table class="notulen-table">
        <thead>
          <tr>
            <th>Judul Notulen</th>
            <th>Agenda</th>
            <th>Tanggal</th>
            <th>Pembuat</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <?php
          // Ambil data dari database
          $query = "SELECT * FROM minutes ORDER BY created_at DESC";
          $result = mysqli_query($koneksi, $query);
          
          while ($row = mysqli_fetch_assoc($result)) {
            $statusClass = 'status-draft';
            $statusText = 'Draft';
            
            // Potong teks agenda jika terlalu panjang
            $agenda = strlen($row['agenda']) > 50 ? substr($row['agenda'], 0, 50) . '...' : $row['agenda'];
            
            echo "<tr data-id='{$row['id']}'>
              <td>{$row['title']}</td>
              <td>{$agenda}</td>
              <td>" . date('d M Y', strtotime($row['created_at'])) . "</td>
              <td>{$row['created_by']}</td>
              <td><span class='status-badge {$statusClass}'>{$statusText}</span></td>
              <td>
                <div class='action-buttons'>
                  <button class='btn-action btn-view' data-id='{$row['id']}'><i class='fas fa-eye'></i> Lihat</button>
                  <button class='btn-action btn-edit' data-id='{$row['id']}'><i class='fas fa-edit'></i> Edit</button>
                  <button class='btn-action btn-download' data-id='{$row['id']}'><i class='fas fa-download'></i> Unduh</button>
                  <button class='btn-action btn-delete' data-id='{$row['id']}'><i class='fas fa-trash'></i> Hapus</button>
                </div>
              </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah/Edit Notulen -->
  <div class="modal" id="modalNotulen">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Tambah Notulen Baru</h2>
        <button class="close-modal">&times;</button>
      </div>
      <form id="formNotulen" action="proses_tambah_notulen.php" method="POST">
        <input type="hidden" id="notulenId" name="id">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="judulNotulen">Judul Notulen *</label>
              <input type="text" id="judulNotulen" name="title" class="form-control" placeholder="Masukkan judul notulen" required>
            </div>
            <div class="form-group">
              <label for="pembuatNotulen">Pembuat Notulen *</label>
              <input type="text" id="pembuatNotulen" name="created_by" class="form-control" placeholder="Nama pembuat notulen" required>
            </div>
          </div>

          <div class="form-group">
            <label for="agendaRapat">Agenda Rapat *</label>
            <textarea id="agendaRapat" name="agenda" class="form-control" placeholder="Masukkan agenda rapat" rows="3" required></textarea>
          </div>

          <div class="form-group">
            <label for="pembahasan">Pembahasan</label>
            <textarea id="pembahasan" name="notes" class="form-control" placeholder="Ringkasan pembahasan dalam rapat" rows="5"></textarea>
          </div>

          <div class="form-group">
            <label for="keputusan">Keputusan</label>
            <textarea id="keputusan" name="decisions" class="form-control" placeholder="Keputusan yang diambil dalam rapat" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label for="tindakLanjut">Tindak Lanjut</label>
            <textarea id="tindakLanjut" name="follow_up" class="form-control" placeholder="Tindak lanjut setelah rapat" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Notulen</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal View Notulen -->
  <div class="modal" id="modalViewNotulen">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Detail Notulen</h2>
        <button class="close-modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="notulen-view" id="notulenViewContent">
          <!-- Content akan diisi oleh JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnTutupView">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnDownloadView">
          <i class="fas fa-download"></i> Download PDF
        </button>
      </div>
    </div>
  </div>

  <script>
    // Elemen DOM
    const modalNotulen = document.getElementById('modalNotulen');
    const modalViewNotulen = document.getElementById('modalViewNotulen');
    const btnTambahNotulen = document.getElementById('btnTambahNotulen');
    const closeModals = document.querySelectorAll('.close-modal');
    const btnBatal = document.getElementById('btnBatal');
    const btnTutupView = document.getElementById('btnTutupView');
    const formNotulen = document.getElementById('formNotulen');
    const tableBody = document.getElementById('tableBody');
    const modalTitle = document.getElementById('modalTitle');
    const notulenIdInput = document.getElementById('notulenId');
    const btnSubmit = document.getElementById('btnSubmit');
    const notulenViewContent = document.getElementById('notulenViewContent');

    let editMode = false;

    // Fungsi untuk membuka modal tambah
    btnTambahNotulen.addEventListener('click', function() {
      editMode = false;
      modalTitle.textContent = "Tambah Notulen Baru";
      btnSubmit.textContent = "Simpan Notulen";
      formNotulen.reset();
      modalNotulen.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });

    // Fungsi untuk membuka modal edit
    function bukaModalEdit(id) {
      editMode = true;
      modalTitle.textContent = "Edit Notulen";
      btnSubmit.textContent = "Update Notulen";
      
      // Ambil data dari baris tabel
      const row = document.querySelector(`tr[data-id="${id}"]`);
      if (row) {
        const cells = row.querySelectorAll('td');
        notulenIdInput.value = id;
        document.getElementById('judulNotulen').value = cells[0].textContent;
        document.getElementById('pembuatNotulen').value = cells[3].textContent;
        
        // Untuk field lainnya, Anda perlu mengambil dari database via AJAX
        // atau menyimpan data dalam atribut data-* pada baris tabel
        
        modalNotulen.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    }

    // Fungsi untuk membuka modal view
    function bukaModalView(id) {
      // Dalam implementasi nyata, Anda akan mengambil data dari database via AJAX
      // Di sini saya menggunakan data dari baris tabel sebagai contoh
      const row = document.querySelector(`tr[data-id="${id}"]`);
      if (row) {
        const cells = row.querySelectorAll('td');
        
        notulenViewContent.innerHTML = `
          <div class="info-group">
            <div class="info-label">Judul Notulen</div>
            <div class="info-value">${cells[0].textContent}</div>
          </div>
          
          <div class="form-row">
            <div class="info-group" style="flex: 1;">
              <div class="info-label">Agenda</div>
              <div class="info-value">${cells[1].textContent}</div>
            </div>
            <div class="info-group" style="flex: 1;">
              <div class="info-label">Tanggal Rapat</div>
              <div class="info-value">${cells[2].textContent}</div>
            </div>
          </div>

          <div class="form-row">
            <div class="info-group" style="flex: 1;">
              <div class="info-label">Pembuat Notulen</div>
              <div class="info-value">${cells[3].textContent}</div>
            </div>
            <div class="info-group" style="flex: 1;">
              <div class="info-label">Status</div>
              <div class="info-value">${cells[4].textContent}</div>
            </div>
          </div>

        `;
        
        modalViewNotulen.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    }

    // Fungsi untuk menutup modal
    function tutupModal() {
      modalNotulen.style.display = 'none';
      modalViewNotulen.style.display = 'none';
      document.body.style.overflow = 'auto';
      formNotulen.reset();
      editMode = false;
    }

    closeModals.forEach(closeBtn => {
      closeBtn.addEventListener('click', tutupModal);
    });
    btnBatal.addEventListener('click', tutupModal);
    btnTutupView.addEventListener('click', tutupModal);

    // Tutup modal ketika klik di luar konten modal
    window.addEventListener('click', function(event) {
      if (event.target === modalNotulen || event.target === modalViewNotulen) {
        tutupModal();
      }
    });

    // Attach event listeners untuk tombol aksi
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          bukaModalView(id);
        });
      });

      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          bukaModalEdit(id);
        });
      });

      document.querySelectorAll('.btn-download').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const row = document.querySelector(`tr[data-id="${id}"]`);
          const title = row.querySelector('td:first-child').textContent;
          alert(`Mengunduh notulen: ${title}`);
        });
      });

      document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const row = document.querySelector(`tr[data-id="${id}"]`);
          const title = row.querySelector('td:first-child').textContent;
          
          if (confirm(`Apakah Anda yakin ingin menghapus notulen: ${title}?`)) {
            // Redirect ke proses hapus
            window.location.href = `proses_hapus_notulen.php?id=${id}`;
          }
        });
      });
    });

    // Fungsi untuk menangani pencarian
    document.querySelector('.search-box input').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.notulen-table tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Export functionality
    document.getElementById('btnExport').addEventListener('click', function() {
      alert('Fitur export data notulen akan dijalankan');
    });

    // Print functionality
    document.getElementById('btnPrint').addEventListener('click', function() {
      window.print();
    });

    // Download PDF dari view modal
    document.getElementById('btnDownloadView').addEventListener('click', function() {
      alert('Mengunduh notulen dalam format PDF');
    });
  </script>
</body>
</html>