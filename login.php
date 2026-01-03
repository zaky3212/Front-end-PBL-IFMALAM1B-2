<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Sistem Pengelolaan Rapat</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
/* ===== Global ===== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: "Poppins", sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #f2e9dc, #fff);
}

/* ===== Container ===== */
.container {
  display: flex;
  flex-wrap: wrap;
  width: 900px;
  max-width: 95%;
  background: rgba(255, 255, 255, 0.35);
  backdrop-filter: blur(15px);
  border-radius: 22px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.5);
  box-shadow: 0 18px 45px rgba(0,0,0,0.2);
  animation: fadeUp 0.8s ease forwards;
}

/* ===== Left Side ===== */
.left-side {
  flex: 1 1 300px;
  padding: 40px;
  background: linear-gradient(180deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
  border-right: 1px solid rgba(255,255,255,0.3);
  color: #3a2c1f;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.left-side h1 {
  font-size: 32px;
  font-weight: 700;
}

.left-side p {
  font-size: 15px;
  margin-top: 15px;
  opacity: 0.9;
}

/* ===== Login Box ===== */
.login-box {
  flex: 1 1 300px;
  padding: 50px 35px;
  background: #fffdfc;
  display: flex;
  flex-direction: column;
  justify-content: center;
  animation: fadeUp 0.8s ease forwards;
  animation-delay: 0.15s;
  opacity: 0;
}

/* ===== Back Button ===== */
.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 20px;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  color: #b37a4c;
  transition: color 0.3s ease, transform 0.3s ease;
}

.back-btn:hover {
  color: #8f603c;
  transform: translateX(-4px);
}

/* ===== Title ===== */
.login-box h2 {
  text-align: center;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 10px;
}

.subtitle {
  text-align: center;
  color: #7a6a5c;
  margin-bottom: 25px;
}

/* ===== Input ===== */
.input-group {
  position: relative;
  margin-bottom: 18px;
}

.input-group i {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  font-size: 16px;
  color: #b37a4c;
}

.input-group input {
  width: 100%;
  padding: 12px 12px 12px 38px;
  border: 1px solid #d5bda7;
  background: #fff8f3;
  border-radius: 10px;
  font-size: 14px;
  transition: 0.3s;
}

.input-group input:focus {
  border-color: #b37a4c;
  outline: none;
  box-shadow: 0 0 6px rgba(179,122,76,0.4);
}

/* ===== Button ===== */
button {
  width: 100%;
  padding: 13px;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  background: #b37a4c;
  color: #fff;
  margin-top: 10px;
  transition: background 0.3s ease, transform 0.3s ease;
}

button:hover {
  background: #8f603c;
  transform: translateY(-2px) scale(1.02);
}

/* ===== Animation ===== */
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== Responsive ===== */
@media (max-width: 900px) {
  .container {
    flex-direction: column;
  }

  .left-side,
  .login-box {
    padding: 25px 20px;
    text-align: center;
  }

  .back-btn {
    align-self: center;
  }

  .left-side {
    border-right: none;
    border-bottom: 1px solid rgba(255,255,255,0.3);
  }
}
</style>
</head>

<body>

<div class="container">

  <div class="left-side">
    <h1>Sistem Pengelolaan Rapat</h1>
    <p>Kelola jadwal, peserta, dan dokumentasi rapat dengan efisien dalam satu platform.</p>
  </div>

  <div class="login-box">
    <a href="index.php" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali ke Beranda
    </a>

    <h2>Selamat Datang</h2>
    <p class="subtitle">Masukkan email dan password untuk melanjutkan</p>

    <form method="POST" action="proses_login.php">
      <div class="input-group">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <div class="input-group">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <button type="submit">Masuk</button>
    </form>
  </div>

</div>

</body>
</html>
