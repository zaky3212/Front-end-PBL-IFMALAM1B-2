<?php
include '../koneksi.php';

// Fungsi untuk memeriksa dan reconnect database
function checkConnection($koneksi) {
    if (!$koneksi || !$koneksi->ping()) {
        // Jika koneksi terputus, buat koneksi baru
        global $servername, $username, $password, $dbname;
        $koneksi = new mysqli($servername, $username, $password, $dbname);
        
        if ($koneksi->connect_error) {
            die("Connection failed: " . $koneksi->connect_error);
        }
    }
    return $koneksi;
}

// Fungsi untuk menambahkan peserta ke database
function addParticipant($koneksi, $data) {
    $koneksi = checkConnection($koneksi);
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $department = mysqli_real_escape_string($koneksi, $data['department']);
    $position = mysqli_real_escape_string($koneksi, $data['position']);
    $phone = mysqli_real_escape_string($koneksi, $data['phone']);
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO participant (name, email, department, position, phone, created_at) 
            VALUES ('$name', '$email', '$department', '$position', '$phone', '$created_at')";
    
    $result = mysqli_query($koneksi, $sql);
    
    if (!$result) {
        error_log("Error adding participant: " . mysqli_error($koneksi));
        return false;
    }
    
    return $result;
}

// Fungsi untuk mengupdate peserta di database
function updateParticipant($koneksi, $id, $data) {
    $koneksi = checkConnection($koneksi);
    $name = mysqli_real_escape_string($koneksi, $data['name']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $department = mysqli_real_escape_string($koneksi, $data['department']);
    $position = mysqli_real_escape_string($koneksi, $data['position']);
    $phone = mysqli_real_escape_string($koneksi, $data['phone']);
    $updated_at = date('Y-m-d H:i:s');

    $sql = "UPDATE participant SET 
            name = '$name',
            email = '$email',
            department = '$department',
            position = '$position',
            phone = '$phone',
            created_at = '$updated_at'
            WHERE id = $id";
    
    $result = mysqli_query($koneksi, $sql);
    
    if (!$result) {
        error_log("Error updating participant: " . mysqli_error($koneksi));
        return false;
    }
    
    return $result;
}

// Proses form tambah/edit peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Validasi data input
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'department' => trim($_POST['department']),
            'position' => trim($_POST['position']),
            'phone' => trim($_POST['phone'])
        ];
        
        // Validasi dasar
        if (empty($data['name']) || empty($data['email']) || empty($data['department']) || empty($data['position'])) {
            header("Location: peserta_admin.php?error=4");
            exit();
        }
        
        if ($action === 'tambah') {
            if (addParticipant($koneksi, $data)) {
                header("Location: peserta_admin.php?success=1");
                exit();
            } else {
                header("Location: peserta_admin.php?error=1");
                exit();
            }
        } elseif ($action === 'edit' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            if (updateParticipant($koneksi, $id, $data)) {
                header("Location: peserta_admin.php?success=2");
                exit();
            } else {
                header("Location: peserta_admin.php?error=2");
                exit();
            }
        }
    }
}

// Jika akses langsung ke file ini, redirect ke halaman peserta
header("Location: peserta_admin.php");
exit();
?>