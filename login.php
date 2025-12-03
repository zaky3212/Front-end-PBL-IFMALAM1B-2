  <?php include 'koneksi.php'; 
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/style.css" >
  </head>
  <body>
    <div class="container">
      <div class="login-box">
        <h1><span class="highlight"></span>Login</h1>
        <h2>LOGIN</h2>
        <p class="subtitle">Enter your credentials to access your account</p>
        
        <form method="POST" action="proses_login.php">
    <input type="email" name="email" placeholder="Masukkan email">
    <input type="password" name="password" placeholder="Masukkan password">
    <button type="submit">Login</button>
  </form>


  </div>

    </div>
  </body>
  </html>
