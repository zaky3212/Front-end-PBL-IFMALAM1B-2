    <?php
    session_start();
    include '../koneksi.php';

    $admin_name = $_SESSION['username'];
    // Fungsi untuk mendapatkan data rapat dari database
    function getMeetings($koneksi)
    {
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
    function getStatus($dates, $start_time, $end_time)
    {
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
    function addMeeting($koneksi, $data)
    {
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
    function updateMeeting($koneksi, $id, $data)
    {
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
    function deleteMeeting($koneksi, $id)
    {
      $sql = "DELETE FROM meetings WHERE id = $id";
      return mysqli_query($koneksi, $sql);
    }

    // Proses form tambah/edit rapat
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $today = date('Y-m-d');

      if ($_POST['dates'] < $today) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=date_invalid");
        exit();
      }
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
        case '1':
          $success_msg = "Rapat berhasil ditambahkan!";
          break;
        case '2':
          $success_msg = "Rapat berhasil diperbarui!";
          break;
        case '3':
          $success_msg = "Rapat berhasil dihapus!";
          break;
      }
    }

    if (isset($_GET['error'])) {
      switch ($_GET['error']) {
        case '1':
          $error_msg = "Gagal menambahkan rapat!";
          break;
        case '2':
          $error_msg = "Gagal memperbarui rapat!";
          break;
        case '3':
          $error_msg = "Gagal menghapus rapat!";
          break;
        case 'date_invalid':
          $error_msg = "Tanggal rapat tidak boleh tanggal yang sudah lewat!";
          break;
      }
    }
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />

      <title>Jadwal Rapat - Admin</title>

      <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

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
          min-height: 100vh;
        }


        /* SIDEBAR */
        .sidebar {
          width: 260px;
          background-color: #f2e9dc;
          display: flex;
          flex-direction: column;
          padding: 40px 25px;

          min-height: 100vh;
          /* biar tidak kepotong */
          height: auto;
          overflow-y: auto;
          /* aktifkan scroll kalau konten panjang */
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

        .menu {
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
          background-color: #e6dccb;
          color: black;
          font-weight: 600;
        }

        .menu i {
          width: 20px;
          text-align: center;
        }

        /* MAIN */
        .main {
          flex: 1;
          background-color: #fff;
          padding: 25px 40px;
          overflow-y: auto;
        }

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

        /* TABLES */
        .jadwal-container {
          display: flex;
          flex-direction: column;
          gap: 25px;
        }

        .jadwal-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
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

        .jadwal-table {
          width: 100%;
          border-collapse: collapse;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
          border-radius: 10px;
          overflow: hidden;
        }

        .jadwal-table th {
          background-color: #f2e9dc;
          padding: 15px;
          text-align: left;
          font-weight: 600;
          color: #333;
        }

        .jadwal-table td {
          padding: 15px;
          border-bottom: 1px solid #eee;
        }

        .jadwal-table tr:hover {
          background-color: #f9f9f9;
        }

        /* STATUS BADGES */
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

        .status-berlangsung {
          background-color: #fff8e5;
          color: #f57c00;
        }

        .status-mendatang {
          background-color: #eaf4ff;
          color: #1976d2;
        }

        /* ACTION BUTTONS */
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
          background-color: #eaf4ff;
          color: #1976d2;
        }

        .btn-edit:hover {
          background-color: #d1e7ff;
        }

        .btn-hapus {
          background-color: #ffeaea;
          color: #d32f2f;
        }

        .btn-hapus:hover {
          background-color: #ffd1d1;
        }

        /* MODAL */
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
        }

        .btn-secondary {
          background-color: #f5f5f5;
          color: #333;
        }

        .btn-primary {
          background-color: #000;
          color: white;
        }

        .btn-primary:hover {
          background-color: #333;
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

        /* Textarea styling */
        textarea.form-control {
          resize: vertical;
          min-height: 100px;
        }

        .logout-box {
          margin-top: auto;
          padding-top: 40px;
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
          transition: 0.3s;
        }


        .logout-btn i {
          font-size: 18px;
        }

        .logout-btn:hover {
          background: #ff4d4d;
          color: #fff;
          border-color: #ff4d4d;
          transform: translateY(-2px);
          box-shadow: 0px 4px 12px rgba(255, 0, 0, 0.25);
        }

        .hamburger {
          display: none;
          background: none;
          border: none;
          cursor: pointer;
        }


        /* MOBILE */
        @media (max-width: 768px) {

          /* tampilkan hamburger */
          .hamburger {
            display: block;
          }

          body {
            display: flex;
            flex-direction: row;
          }

          /* sidebar disembunyikan */
          .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background-color: #f2e9dc;
            z-index: 2000;

            transform: translateX(-100%);
            transition: transform 0.3s ease;
          }

          /* sidebar muncul */
          .sidebar.active {
            transform: translateX(0);
          }

          /* konten full layar */
          .main {
            width: 100%;
            padding: 20px;
          }
        }

        .table-responsive {
          width: 100%;
          overflow-x: auto;
        }

        @media (max-width: 768px) {
          .sidebar {
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
          }
        }

        /* OVERLAY UNTUK MOBILE */
        .overlay {
          display: none;
          position: fixed;
          inset: 0;
          background: rgba(0, 0, 0, 0.3);
          z-index: 1500;
        }

        /* MOBILE */
        @media (max-width: 768px) {
          .overlay.active {
            display: block;
          }
        }
      </style>
    </head>

    <body>
      <!-- Sidebar -->
      <div class="sidebar">

        <h2>Pengelolaan Rapat</h2>
        <h3>Selamat datang, <?= $admin_name ?>!</h3>
        <div class="menu">
          <a href="dashboard_admin.php"><i class="fas fa-home"></i> Home</a>
          <a href="jadwal_admin.php" class="active"><i class="fas fa-calendar-alt"></i> Jadwal</a>
          <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
          <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
          <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
          <a href="tambah_akun.php"><i class="fas fa-file-alt"></i> Tambah Akun</a>
        </div>
        <div class="logout-box">
          <a href="../logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Keluar
          </a>
        </div>
      </div>

      <div class="overlay" id="overlay"></div>

      <!-- Main Content -->
      <div class="main">

        <div class="topbar">
          <button class="hamburger" id="hamburgerBtn">☰</button>
          <h1>Jadwal Rapat</h1>

          <div class="search-box">
            <input type="text" placeholder="Search Jadwal..." id="searchInput">
            <i class="fas fa-search"></i>
          </div>
        </div>

        <div class="jadwal-container">

          <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
          <?php endif; ?>

          <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error"><?= $error_msg ?></div>
          <?php endif; ?>

          <div class="jadwal-header">
            <h2>Daftar Rapat</h2>
            <button class="btn-tambah" id="btnTambahRapat">
              <i class="fas fa-plus"></i> Tambah Rapat
            </button>
          </div>

          <!-- TABLE WAJIB DI DALAM MAIN -->
          <div class="table-responsive">
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
                    $status = !empty($meeting['status_meetings'])
                      ? $meeting['status_meetings']
                      : getStatus($meeting['dates'], $meeting['start_time'], $meeting['end_time']);

                    $statusClass = match ($status) {
                      'Selesai' => 'status-selesai',
                      'Berlangsung' => 'status-berlangsung',
                      default => 'status-mendatang'
                    };
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($meeting['title']) ?></td>
                      <td><?= htmlspecialchars($meeting['descriptions']) ?></td>
                      <td><?= date('d M Y', strtotime($meeting['dates'])) ?></td>
                      <td><?= $meeting['start_time'] ?> - <?= $meeting['end_time'] ?></td>
                      <td><?= htmlspecialchars($meeting['locations']) ?></td>
                      <td><?= htmlspecialchars($meeting['leader']) ?></td>
                      <td>
                        <span class="status-badge <?= $statusClass ?>">
                          <?= $status ?>
                        </span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-action btn-edit"
                            data-id="<?= $meeting['id'] ?>"
                            data-title="<?= htmlspecialchars($meeting['title']) ?>"
                            data-descriptions="<?= htmlspecialchars($meeting['descriptions']) ?>"
                            data-dates="<?= $meeting['dates'] ?>"
                            data-start-time="<?= $meeting['start_time'] ?>"
                            data-end-time="<?= $meeting['end_time'] ?>"
                            data-locations="<?= htmlspecialchars($meeting['locations']) ?>"
                            data-leader="<?= htmlspecialchars($meeting['leader']) ?>">
                            <i class="fas fa-edit"></i> Edit
                          </button>

                          <a href="?hapus=<?= $meeting['id'] ?>"
                            class="btn-action btn-hapus"
                            onclick="return confirm('Yakin hapus rapat ini?')">
                            <i class="fas fa-trash"></i> Hapus
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" style="text-align:center;">Tidak ada data rapat</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

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
                <input type="date" id="dates" name="dates" class="form-control" required min="<?= date('Y-m-d') ?>">
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
        const dateInput = document.getElementById('dates');

        dateInput.addEventListener('change', function() {
          const today = new Date();
          today.setHours(0, 0, 0, 0);

          const selectedDate = new Date(this.value);

          if (selectedDate < today) {
            alert('Tanggal rapat tidak boleh kurang dari hari ini!');
            this.value = '';
          }
        });
      </script>


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

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const hamburgerBtn = document.getElementById('hamburgerBtn');
          const sidebar = document.querySelector('.sidebar');
          const overlay = document.getElementById('overlay');

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
            sidebar.classList.contains('active') ? closeSidebar() : openSidebar();
          });

          overlay.addEventListener('click', closeSidebar);
        });
      </script>

    </body>

    </html>