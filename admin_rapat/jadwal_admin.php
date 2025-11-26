<?php 
include '../koneksi.php';

// Fungsi untuk mendapatkan data rapat dari database
function getMeetings($koneksi) {
    $sql = "SELECT * FROM meetings ORDER BY id DESC";
    $result = mysqli_query($koneksi, $sql);
    
    $meetings = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $meetings[] = $row;
        }
    }
    return $meetings;
}

// Fungsi untuk menentukan status rapat
function getStatus($dates, $start_time, $end_time) {
    $now = time();
    $meetingStart = strtotime($dates . ' ' . $start_time);
    $meetingEnd = strtotime($dates . ' ' . $end_time);
    
    if ($now > $meetingEnd) {
        return 'Selesai';
    } elseif ($now >= $meetingStart && $now <= $meetingEnd) {
        return 'Berlangsung';
    } else {
        return 'Mendatang';
    }
}

// Fungsi untuk menambahkan rapat ke database
function addMeeting($koneksi, $data) {
    $title = mysqli_real_escape_string($koneksi, $data['title']);
    $descriptions = mysqli_real_escape_string($koneksi, $data['descriptions']);
    $dates = mysqli_real_escape_string($koneksi, $data['dates']);
    $start_time = mysqli_real_escape_string($koneksi, $data['start_time']);
    $end_time = mysqli_real_escape_string($koneksi, $data['end_time']);
    $locations = mysqli_real_escape_string($koneksi, $data['locations']);
    $leader = mysqli_real_escape_string($koneksi, $data['leader']);
    $created_by = mysqli_real_escape_string($koneksi, $data['created_by']);
    $created_at = date('Y-m-d H:i:s');

    // Tentukan status berdasarkan waktu
    $status_meetings = getStatus($dates, $start_time, $end_time);
    
    $sql = "INSERT INTO meetings (title, descriptions, dates, start_time, end_time, locations, leader, status_meetings, created_by, created_at) 
            VALUES ('$title', '$descriptions', '$dates', '$start_time', '$end_time', '$locations', '$leader', '$status_meetings', '$created_by', '$created_at')";
    
    return mysqli_query($koneksi, $sql);
}

// Fungsi untuk mengupdate rapat di database
function updateMeeting($koneksi, $id, $data) {
    $title = mysqli_real_escape_string($koneksi, $data['title']);
    $descriptions = mysqli_real_escape_string($koneksi, $data['descriptions']);
    $dates = mysqli_real_escape_string($koneksi, $data['dates']);
    $start_time = mysqli_real_escape_string($koneksi, $data['start_time']);
    $end_time = mysqli_real_escape_string($koneksi, $data['end_time']);
    $locations = mysqli_real_escape_string($koneksi, $data['locations']);
    $leader = mysqli_real_escape_string($koneksi, $data['leader']);
    $created_by = mysqli_real_escape_string($koneksi, $data['created_by']);
    $created_at = date('Y-m-d H:i:s');

    // Tentukan status berdasarkan waktu
    $status_meetings = getStatus($dates, $start_time, $end_time);
    
    $sql = "UPDATE meetings SET 
            title = '$title',
            descriptions = '$descriptions',
            dates = '$dates',
            start_time = '$start_time',
            end_time = '$end_time',
            locations = '$locations',
            leader = '$leader',
            status_meetings = '$status_meetings',
            created_by = '$created_by',
            created_at = '$created_at'
            WHERE id = $id";
    
    return mysqli_query($koneksi, $sql);
}

// Fungsi untuk menghapus rapat dari database
function deleteMeeting($koneksi, $id) {
    $sql = "DELETE FROM meetings WHERE id = $id";
    return mysqli_query($koneksi, $sql);
}

// Proses form tambah/edit rapat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        $data = [
            'title' => $_POST['title'],
            'descriptions' => $_POST['descriptions'],
            'dates' => $_POST['dates'],
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'locations' => $_POST['locations'],
            'leader' => $_POST['leader'],
            'created_by' => 'Admin' // Anda bisa mengganti ini dengan session user yang login
        ];
        
        if ($action === 'tambah') {
            if (addMeeting($koneksi, $data)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
                exit();
            }
        } elseif ($action === 'edit' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            if (updateMeeting($koneksi, $id, $data)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=2");
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=2");
                exit();
            }
        }
    }
}

// Proses hapus rapat
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (deleteMeeting($koneksi, $id)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=3");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=3");
        exit();
    }
}

// Ambil data rapat dari database
$meetings = getMeetings($koneksi);

// Pesan sukses/error
$success_msg = "";
$error_msg = "";

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $success_msg = "Rapat berhasil ditambahkan!"; break;
        case '2': $success_msg = "Rapat berhasil diperbarui!"; break;
        case '3': $success_msg = "Rapat berhasil dihapus!"; break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1': $error_msg = "Gagal menambahkan rapat!"; break;
        case '2': $error_msg = "Gagal memperbarui rapat!"; break;
        case '3': $error_msg = "Gagal menghapus rapat!"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/style_jadwal_admin.css">
  <title>Jadwal Rapat - Admin</title>
  
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Pengelolaan Rapat</h2>
    <div class="menu">
      <a href="dashboard_admin.php"><i class="fas fa-home"></i> Home</a>
      <a href="jadwal_admin.php" class="active"><i class="fas fa-calendar-alt"></i> Jadwal</a>
      <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
      <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
      <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="topbar">
      <h1>Jadwal Rapat</h1>
      <div class="search-box">
        <input type="text" placeholder="Search..." id="searchInput">
        <i class="fas fa-search"></i>
      </div>
    </div>

    <div class="jadwal-container">
      <!-- Alert Messages -->
      <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php endif; ?>
      
      <?php if (!empty($error_msg)): ?>
        <div class="alert alert-error"><?php echo $error_msg; ?></div>
      <?php endif; ?>

      <div class="jadwal-header">
        <h2>Daftar Rapat</h2>
        <button class="btn-tambah" id="btnTambahRapat"><i class="fas fa-plus"></i> Tambah Rapat</button>
      </div>

      <table class="jadwal-table">
        <thead>
          <tr>
            <th>Judul Rapat</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Lokasi</th>
            <th>Pemimpin</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <?php if (count($meetings) > 0): ?>
            <?php foreach ($meetings as $meeting): ?>
              <?php
              // Gunakan status dari database atau hitung jika kosong
              $status = !empty($meeting['status_meetings']) ? $meeting['status_meetings'] : getStatus($meeting['dates'], $meeting['start_time'], $meeting['end_time']);
              $statusClass = '';
              switch ($status) {
                case 'Selesai': $statusClass = 'status-selesai'; break;
                case 'Berlangsung': $statusClass = 'status-berlangsung'; break;
                case 'Mendatang': $statusClass = 'status-mendatang'; break;
              }
              ?>
              <tr>
                <td><?php echo htmlspecialchars($meeting['title']); ?></td>
                <td><?php echo htmlspecialchars($meeting['descriptions']); ?></td>
                <td><?php echo date('d M Y', strtotime($meeting['dates'])); ?></td>
                <td><?php echo $meeting['start_time'] . ' - ' . $meeting['end_time']; ?></td>
                <td><?php echo htmlspecialchars($meeting['locations']); ?></td>
                <td><?php echo htmlspecialchars($meeting['leader']); ?></td>
                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-edit" data-id="<?php echo $meeting['id']; ?>" 
                            data-title="<?php echo htmlspecialchars($meeting['title']); ?>"
                            data-descriptions="<?php echo htmlspecialchars($meeting['descriptions']); ?>"
                            data-dates="<?php echo $meeting['dates']; ?>"
                            data-start-time="<?php echo $meeting['start_time']; ?>"
                            data-end-time="<?php echo $meeting['end_time']; ?>"
                            data-locations="<?php echo htmlspecialchars($meeting['locations']); ?>"
                            data-leader="<?php echo htmlspecialchars($meeting['leader']); ?>">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="?hapus=<?php echo $meeting['id']; ?>" class="btn-action btn-hapus" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus rapat ini?')">
                      <i class="fas fa-trash"></i> Hapus
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align: center;">Tidak ada data rapat</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah/Edit -->
  <div class="modal" id="modalTambahRapat">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Tambah Rapat Baru</h2>
        <button class="close-modal">&times;</button>
      </div>
      <form id="formTambahRapat" method="POST" action="">
        <input type="hidden" id="action" name="action" value="tambah">
        <input type="hidden" id="meetingId" name="id" value="">
        <div class="modal-body">
          <div class="form-group">
            <label>Judul Rapat *</label>
            <input type="text" id="title" name="title" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="descriptions" name="descriptions" class="form-control" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Tanggal *</label>
            <input type="date" id="dates" name="dates" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Waktu Mulai *</label>
            <input type="time" id="start_time" name="start_time" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Waktu Selesai *</label>
            <input type="time" id="end_time" name="end_time" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Lokasi *</label>
            <input type="text" id="locations" name="locations" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Pemimpin *</label>
            <input type="text" id="leader" name="leader" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Rapat</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('modalTambahRapat');
    const btnTambah = document.getElementById('btnTambahRapat');
    const closeModal = document.querySelector('.close-modal');
    const btnBatal = document.getElementById('btnBatal');
    const form = document.getElementById('formTambahRapat');
    const modalTitle = document.getElementById('modalTitle');
    const btnSimpan = document.getElementById('btnSimpan');
    const actionInput = document.getElementById('action');
    const meetingIdInput = document.getElementById('meetingId');

    function openModal(isEdit = false, data = null) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      
      if (isEdit && data) {
        modalTitle.textContent = 'Edit Rapat';
        btnSimpan.textContent = 'Perbarui Rapat';
        actionInput.value = 'edit';
        meetingIdInput.value = data.id;
        
        document.getElementById('title').value = data.title;
        document.getElementById('descriptions').value = data.descriptions;
        document.getElementById('dates').value = data.dates;
        document.getElementById('start_time').value = data.start_time;
        document.getElementById('end_time').value = data.end_time;
        document.getElementById('locations').value = data.locations;
        document.getElementById('leader').value = data.leader;
      } else {
        modalTitle.textContent = 'Tambah Rapat Baru';
        btnSimpan.textContent = 'Simpan Rapat';
        actionInput.value = 'tambah';
        meetingIdInput.value = '';
        form.reset();
      }
    }

    function closeModalFunc() {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    btnTambah.addEventListener('click', () => openModal());
    closeModal.addEventListener('click', closeModalFunc);
    btnBatal.addEventListener('click', closeModalFunc);

    // Event listener untuk tombol edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const data = {
          id: e.target.closest('.btn-edit').dataset.id,
          title: e.target.closest('.btn-edit').dataset.title,
          descriptions: e.target.closest('.btn-edit').dataset.descriptions,
          dates: e.target.closest('.btn-edit').dataset.dates,
          start_time: e.target.closest('.btn-edit').dataset.start_time,
          end_time: e.target.closest('.btn-edit').dataset.end_time,
          locations: e.target.closest('.btn-edit').dataset.locations,
          leader: e.target.closest('.btn-edit').dataset.leader
        };
        openModal(true, data);
      });
    });

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchText = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tableBody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
      });
    });
  </script>
</body>
</html>