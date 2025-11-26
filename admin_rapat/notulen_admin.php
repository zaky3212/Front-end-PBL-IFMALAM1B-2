  <?php include '../koneksi.php'; ?>

  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style_notulen_admin.css">
    <title>Notulen Rapat - Admin</title>
    
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