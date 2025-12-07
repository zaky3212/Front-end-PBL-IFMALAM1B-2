<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengelolaan Rapat</title>
    <link rel="stylesheet" href="assets/style2.css">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>

<body>

<div class="container">

    
    <div class="left-side">

        <h1>Sistem Pengelolaan Rapat</h1>
        <p>Kelola jadwal, peserta, dan dokumentasi rapat dengan efisien dalam satu platform.</p>

    </div>

    
    <div class="login-box">
        <h2>Selamat Datang</h2>
        <p class="subtitle">Masukkan email dan password untuk melanjutkan</p>

        <form method="POST" action="proses_login.php">

           
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input id="email" type="email" name="email" placeholder="Email" required>
            </div>

            
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input id="password" type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit">Masuk</button>
        </form>
    </div>

</div>

</body>
</html>
