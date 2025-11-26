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
      
  <form action="proses_login.php" method="post">
    <label for="username">Username</label>
    <input type="text" id="username"  name = "username" placeholder="Enter your email" required>
    
    <label for="password">Password</label>
    <input type="password" id="password"  name = "password" placeholder="Enter your password" required>

    <button type="submit">Log in</button>
  </form>

</div>

  </div>
</body>
</html>
