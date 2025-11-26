  <?php include '../koneksi.php'; ?>

  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style_dashboard_admin.css">
    <title>Pengelolaan Rapat - Admin</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Pengelolaan Rapat</h2>
      <div class="menu">
        <a href="dashboard_admin.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
        <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
        <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
        <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
    
      </div>
    </div>

    <!-- Main Content -->
    <div class="main">
      <div class="topbar">
        <h1>Admin</h1>
        <div class="right">
          <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class="fas fa-search"></i>
          </div>
          <i class="fas fa-bell bell"></i>
        </div>
      </div>

      <!-- Cards -->
      <div class="card-container">
        <div class="card blue">
          <i class="fas fa-user"></i>
          <p>Peserta</p>
        </div>
        <div class="card pink">
          <i class="fas fa-calendar"></i>
          <p>Jadwal</p>
        </div>
        <div class="card yellow">
          <i class="fas fa-file-alt"></i>
          <p>Notulen</p>
        </div>
        <div class="card orange">
          <i class="fas fa-user-shield"></i>
          <p>Admin</p>
        </div>
      </div>
    </div>
  </body>
  </html>
