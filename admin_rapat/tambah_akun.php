<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
$admin_name = $_SESSION['username'];
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Akun Peserta</title>
    <link rel="stylesheet" href="../assets/style_dashboard_admin.css">

    <style>
        .content-center {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        /* ===== FORM ===== */
        .form-box {
            background: linear-gradient(180deg, #ffffff, #f7fbff);
            padding: 30px;
            border-radius: 16px;
            width: 600px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .12);
            border: 1px solid #e6eef7;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d0d7e2;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #00cfe8;
            box-shadow: 0 0 0 3px rgba(0, 207, 232, .2);
        }

        /* password */
        .password-box {
            position: relative;
        }

        .toggle-pass {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* button */
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
            border: 1px solid #d0d7e2;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .btn-submit {
            flex: 2;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #00cfe8, #1de9b6);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        /* search */
        .search-akun {
            width: 300px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin: 30px 0;
        }

        /* table */
        .table-modern {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
        }

        .table-modern th {
            background: #00cfe8;
            color: #fff;
            padding: 12px;
        }

        .table-modern td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .table-modern tr:hover {
            background: #f9f9f9;
        }

        .btn-edit {
            background: #ffc107;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: #000;
        }

        .btn-delete {
            background: #ff4d4d;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
        }

        @media (max-width: 768px) {

body {
  overflow-x: hidden;
  overflow-y: auto !important;
}

.main {
  margin-left: 0 !important;
  width: 100% !important;
  padding: 20px 15px;
}

.content-center {
  margin-top: 15px;
}

.form-box {
  width: 100%;
  padding: 20px;
}

.form-actions {
  flex-direction: column;
}

.btn-back,
.btn-submit {
  width: 100%;
}

.search-akun {
  width: 100%;
}

.table-modern {
  display: block;
  overflow-x: auto;
  white-space: nowrap;
}

.table-modern th,
.table-modern td {
  font-size: 13px;
  padding: 10px;
}

h2 {
  font-size: 20px;
}
}


    </style>
</head>

<body>
    <div class="main">
        <h2>Buat Akun Peserta</h2>

        <div class="content-center">
            <div class="form-box">

                <?php if (isset($_GET['success'])) { ?>
                    <div style="background:#d4edda;padding:10px;border-radius:8px">✅ Akun berhasil dibuat</div>
                <?php } ?>

                <?php if (isset($_GET['error'])) { ?>
                    <div style="background:#f8d7da;padding:10px;border-radius:8px">❗ <?= htmlspecialchars($_GET['error']) ?></div>
                <?php } ?>

                <form action="proses_buat_akun_peserta.php" method="POST">

                    <div class="form-group">
                        <label>Pilih Peserta</label>
                        <select name="participant_id" required>
                            <option value="">-- Pilih --</option>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT * FROM participant ORDER BY name ASC");
                            while ($p = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$p['id']}'>{$p['name']} ({$p['email']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group password-box">
                        <label>Password</label>
                        <input type="password" name="password" id="password" required>
                        <span class="toggle-pass" onclick="togglePassword()">👁</span>
                    </div>

                    <div class="form-actions">
                        <a href="dashboard_admin.php" class="btn-back">← Kembali</a>
                        <button type="submit" class="btn-submit">Buat Akun</button>
                    </div>

                </form>
            </div>
        </div>

        <!-- SEARCH -->
        <form method="GET">
            <input type="text" name="search" class="search-akun"
                placeholder="Cari nama / username / email"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>

        <!-- TABLE -->
        <table class="table-modern">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>

            <?php
            $search = mysqli_real_escape_string($koneksi, $_GET['search'] ?? '');
            $where = $search ? "AND (participant.name LIKE '%$search%' 
OR users.username LIKE '%$search%' 
OR users.email LIKE '%$search%')" : "";

            $data = mysqli_query($koneksi, "
SELECT users.*, participant.name
FROM users
JOIN participant ON users.participant_id=participant.id
WHERE users.role='user' $where
ORDER BY users.id DESC
");

            $no = 1;
            if (mysqli_num_rows($data) == 0) {
                echo "<tr><td colspan='5' align='center'>Data tidak ditemukan</td></tr>";
            }

            while ($u = mysqli_fetch_assoc($data)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <a href="edit_akun.php?id=<?= $u['id'] ?>" class="btn-edit">Edit</a>
                        <a href="hapus_akun.php?id=<?= $u['id'] ?>" onclick="return confirm('Yakin hapus?')" class="btn-delete">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>

    <script>
        function togglePassword() {
            const p = document.getElementById("password");
            p.type = p.type === "password" ? "text" : "password";
        }
    </script>

</body>

</html>