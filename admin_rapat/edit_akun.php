<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
$u = mysqli_fetch_assoc($query);

if (!$u) {
    header("Location: tambah_akun.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Akun Peserta</title>
    <link rel="stylesheet" href="../assets/style_dashboard_admin.css">

    <style>
        .content-center {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .edit-box {
            width: 450px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,.12);
        }

        .edit-box h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #444;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d0d7e2;
        }

        .form-group small {
            color: #777;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00cfe8;
            box-shadow: 0 0 0 3px rgba(0,207,232,.2);
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-back {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            background: #f1f3f6;
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-save {
            flex: 2;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #00cfe8, #1de9b6);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-back:hover {
            background: #e0e4ea;
        }

        .btn-save:hover {
            opacity: .9;
        }
    </style>
</head>

<body>

<div class="main">
    <h1>Edit Akun Peserta</h1>

    <div class="content-center">
        <div class="edit-box">

            <h2>Form Edit Akun</h2>

            <form method="POST" action="proses_edit_akun.php">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username"
                           value="<?= htmlspecialchars($u['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($u['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password">
                    <small>Kosongkan jika tidak ingin mengganti password</small>
                </div>

                <div class="form-actions">
                    <a href="tambah_akun.php" class="btn-back">← Kembali</a>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
