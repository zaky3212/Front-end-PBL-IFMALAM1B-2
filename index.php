<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Pengelolaan Rapat</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #f2e9dc, #fff);
  color: #3a2c1f;
}


.navbar {
  position: sticky;
  top: 0;
  z-index: 10;
  background: rgba(255,255,255,0.75);
  backdrop-filter: blur(12px);
  padding: 15px 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

.nav-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  font-size: 20px;
}

.nav-brand i {
  color: #b37a4c;
}

.nav-menu {
  display: flex;
  gap: 25px;
  align-items: center;
}

.nav-menu a {
  text-decoration: none;
  font-weight: 500;
  color: #3a2c1f;
  transition: 0.3s;
}

.nav-menu a:hover {
  color: #b37a4c;
}

.nav-menu .btn-login {
  padding: 8px 20px;
  background: #b37a4c;
  color: #fff;
  border-radius: 10px;
  font-weight: 600;
}

.nav-menu .btn-login:hover {
  background: #8f603c;
}

/* ===== Hero ===== */
.hero {
  min-height: 90vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.hero-box {
  width: 1100px;
  max-width: 95%;
  display: flex;
  flex-wrap: wrap;
  background: rgba(255,255,255,0.35);
  backdrop-filter: blur(15px);
  border-radius: 22px;
  box-shadow: 0 18px 45px rgba(0,0,0,0.2);
  overflow: hidden;
}

.hero-text {
  flex: 1 1 400px;
  padding: 60px;
}

.hero-text h1 {
  font-size: 36px;
  margin-bottom: 15px;
}

.hero-text p {
  line-height: 1.7;
  margin-bottom: 30px;
  opacity: 0.9;
}

.hero-btn {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.hero-btn a {
  text-decoration: none;
  padding: 14px 28px;
  border-radius: 12px;
  font-weight: 600;
}

.btn-primary {
  background: #b37a4c;
  color: #fff;
}

.btn-secondary {
  border: 2px solid #b37a4c;
  color: #b37a4c;
}

.btn-primary:hover {
  background: #8f603c;
}

.btn-secondary:hover {
  background: #fff8f3;
}

/* ===== Feature Section ===== */
.features {
  padding: 80px 20px;
  max-width: 1100px;
  margin: auto;
}

.features h2 {
  text-align: center;
  margin-bottom: 50px;
  font-size: 30px;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 25px;
}

.feature-card {
  background: #fffdfc;
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #d5bda7;
  text-align: center;
  transition: 0.3s;
}

.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.feature-card i {
  font-size: 28px;
  color: #b37a4c;
  margin-bottom: 15px;
}

.feature-card h4 {
  margin-bottom: 10px;
}


footer {
  text-align: center;
  padding: 25px;
  font-size: 13px;
  opacity: 0.7;
}


@media (max-width: 768px) {
  .navbar {
    padding: 15px 25px;
  }

  .hero-text {
    padding: 40px 25px;
    text-align: center;
  }

  .hero-btn {
    justify-content: center;
  }
}


.page-transition {
  animation: fadeInPage 0.6s ease forwards;
}

.fade-out {
  animation: fadeOutPage 0.45s ease forwards;
}

@keyframes fadeInPage {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeOutPage {
  from {
    opacity: 1;
    transform: translateY(0);
  }
  to {
    opacity: 0;
    transform: translateY(-10px);
  }
}

</style>
</head>

<body class="page-transition">

<!-- Navbar -->
<nav class="navbar">
  <div class="nav-brand">
    <i class="fa-solid fa-handshake"></i>
    <span>PENGELOLAAN RAPAT</span>
  </div>
  <div class="nav-menu">
    <a href="#">Beranda</a>
    <a href="#fitur">Fitur</a>
    <a href="login.php" class="btn-login">Masuk</a>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="hero-box">
    <div class="hero-text">
      <h1>Sistem Pengelolaan Rapat Modern</h1>
      <p>
        Solusi digital untuk mengelola rapat secara profesional,
        mulai dari penjadwalan, peserta, hingga notulen dan arsip rapat.
      </p>
      <div class="hero-btn">
        <a href="login.php" class="btn-primary">Mulai Sekarang</a>
        <a href="#fitur" class="btn-secondary">Lihat Fitur</a>
      </div>
    </div>
  </div>
</section>

<!-- Features -->
<section class="features" id="fitur">
  <h2>Kenapa Menggunakan Sistem Ini?</h2>
  <div class="feature-grid">

    <div class="feature-card">
      <i class="fa-solid fa-calendar-check"></i>
      <h4>Penjadwalan Rapi</h4>
      <p>Atur jadwal rapat tanpa bentrok dan mudah dipantau.</p>
    </div>

    <div class="feature-card">
      <i class="fa-solid fa-users"></i>
      <h4>Manajemen Peserta</h4>
      <p>Kelola peserta berdasarkan unit atau jabatan.</p>
    </div>

    <div class="feature-card">
      <i class="fa-solid fa-file-lines"></i>
      <h4>Notulen Digital</h4>
      <p>Notulen tersimpan aman dan mudah diakses.</p>
    </div>

    <div class="feature-card">
      <i class="fa-solid fa-folder-open"></i>
      <h4>Dokumentasi Terpusat</h4>
      <p>Semua arsip rapat tersimpan dalam satu sistem.</p>
    </div>

  </div>
</section>

<footer>
  © 2026 Sistem Pengelolaan Rapat — All Rights Reserved
</footer>

<script>
  document.querySelectorAll("a").forEach(link => {
    if (link.getAttribute("href") && !link.getAttribute("href").startsWith("#")) {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const target = this.href;

        document.body.classList.add("fade-out");

        setTimeout(() => {
          window.location.href = target;
        }, 400);
      });
    }
  });
</script>

</body>
</html>
